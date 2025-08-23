<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class User extends Model
{
    protected static string $table = 'users';
    
    public static function findById(int $id): ?array
    {
        return Database::fetchOne(
            "SELECT * FROM " . self::$table . " WHERE id = ? AND is_active = 1",
            [$id]
        );
    }

    public static function findByUsername(string $username): ?array
    {
        return Database::fetchOne(
            "SELECT * FROM " . self::$table . " WHERE username = ? AND is_active = 1",
            [$username]
        );
    }
    
    public static function findByEmail(string $email): ?array
    {
        return Database::fetchOne(
            "SELECT * FROM " . self::$table . " WHERE email = ? AND is_active = 1",
            [$email]
        );
    }
    
    public static function authenticate(string $username, string $password): ?array
    {
        $user = self::findByUsername($username);
        
        if ($user && password_verify($password, $user['password_hash'])) {
            // Update last login
            self::updateLastLogin($user['id']);
            return $user;
        }
        
        return null;
    }
    
    public static function create(array $data): int
    {
        // Hash password if provided
        if (isset($data['password'])) {
            $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
            unset($data['password']);
        }
        
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        return Database::insert(self::$table, $data);
    }
    
    public static function updateLastLogin(int $userId): int
    {
        return Database::query(
            "UPDATE " . self::$table . " SET last_login = CURRENT_TIMESTAMP WHERE id = ?",
            [$userId]
        )->rowCount();
    }
    
    public static function changePassword(int $userId, string $newPassword): int
    {
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        
        return Database::query(
            "UPDATE " . self::$table . " SET password_hash = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?",
            [$passwordHash, $userId]
        )->rowCount();
    }
    
    public static function activate(int $userId): int
    {
        return Database::query(
            "UPDATE " . self::$table . " SET is_active = 1, updated_at = CURRENT_TIMESTAMP WHERE id = ?",
            [$userId]
        )->rowCount();
    }
    
    public static function deactivate(int $userId): int
    {
        return Database::query(
            "UPDATE " . self::$table . " SET is_active = 0, updated_at = CURRENT_TIMESTAMP WHERE id = ?",
            [$userId]
        )->rowCount();
    }
    
    public static function getActive(): array
    {
        return Database::fetchAll(
            "SELECT id, username, email, first_name, last_name, role, last_login, created_at FROM " . self::$table . " WHERE is_active = 1 ORDER BY username"
        );
    }
    
    public static function count(): int
    {
        $result = Database::fetchOne("SELECT COUNT(*) as count FROM " . self::$table . " WHERE is_active = 1");
        return $result['count'] ?? 0;
    }
}