<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class User extends Model
{
    protected static string $table = 'users';
    protected static array $fillable = [
        'username', 'email', 'password', 'first_name', 'last_name', 
        'role', 'is_active', 'magic_link_token', 'magic_link_expires'
    ];
    protected static array $allowHtml = [];
    
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
    
    public static function count(?int $userId = null): int
    {
        $sql = "SELECT COUNT(*) as count FROM " . self::$table . " WHERE is_active = 1";
        $params = [];
        
        // For User model, we typically don't filter by user_id since it's an admin resource
        // but we keep the parameter for compatibility with the parent class
        
        $result = Database::fetchOne($sql, $params);
        return $result['count'] ?? 0;
    }

    public static function storeMagicLinkToken(int $userId, string $token, int $expires): int
    {
        return Database::query(
            "UPDATE " . self::$table . " SET 
                magic_link_token = ?, 
                magic_link_expires = datetime(?, 'unixepoch'), 
                updated_at = CURRENT_TIMESTAMP 
            WHERE id = ?",
            [$token, $expires, $userId]
        )->rowCount();
    }

    public static function findByMagicLinkToken(string $token): ?array
    {
        return Database::fetchOne(
            "SELECT *, strftime('%s', magic_link_expires) as magic_link_expires FROM " . self::$table . " 
            WHERE magic_link_token = ? 
            AND magic_link_expires > datetime('now') 
            AND is_active = 1",
            [$token]
        );
    }

    public static function clearMagicLinkToken(int $userId): int
    {
        return Database::query(
            "UPDATE " . self::$table . " SET 
                magic_link_token = NULL, 
                magic_link_expires = NULL, 
                updated_at = CURRENT_TIMESTAMP 
            WHERE id = ?",
            [$userId]
        )->rowCount();
    }
}