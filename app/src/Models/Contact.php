<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;
use App\DTOs\ContactDTO;

class Contact extends Model
{
    protected static string $table = 'contacts';
    protected static array $fillable = ['name', 'email', 'phone', 'service_type', 'message', 'status'];
    protected static array $allowHtml = ['message'];

    public static function find(int $id): ?ContactDTO
    {
        $data = Database::fetchOne("SELECT * FROM " . static::$table . " WHERE id = ?", [$id]);
        return $data ? new ContactDTO($data) : null;
    }
    
    public static function getByStatus(string $status): array
    {
        $allData = Database::fetchAll(
            "SELECT * FROM " . self::$table . " WHERE status = ? ORDER BY created_at DESC",
            [$status]
        );
        return array_map(fn($data) => new ContactDTO($data), $allData);
    }
    
    public static function getRecent(int $limit = 10): array
    {
        $allData = Database::fetchAll(
            "SELECT * FROM " . self::$table . " ORDER BY created_at DESC LIMIT ?",
            [$limit]
        );
        return array_map(fn($data) => new ContactDTO($data), $allData);
    }
    
    public static function all(?int $userId = null): array
    {
        $sql = "SELECT * FROM " . self::$table;
    }

    public static function getPaginated(string $status, int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;

        $where = '';
        $params = [];
        if ($status !== 'all') {
            $where = ' WHERE status = ?';
            $params[] = $status;
        }

        $total = Database::fetchOne("SELECT COUNT(*) as count FROM " . self::$table . $where, $params)['count'];

        $data = Database::fetchAll(
            "SELECT * FROM " . self::$table . $where . " ORDER BY created_at DESC LIMIT ? OFFSET ?",
            array_merge($params, [$perPage, $offset])
        );

        return [
            'items' => array_map(fn($d) => new ContactDTO($d), $data),
            'total' => $total,
        ];
        $params = [];
        
        // Add user_id filter if provided and column exists
        if ($userId !== null && static::hasColumn('user_id')) {
            $sql .= " WHERE user_id = :user_id";
            $params['user_id'] = $userId;
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        $allData = Database::fetchAll($sql, $params);
        return array_map(fn($data) => new ContactDTO($data), $allData);
    }
    
    public static function count(?int $userId = null): int
    {
        $sql = "SELECT COUNT(*) as count FROM " . self::$table;
        $params = [];
        
        // Add user_id filter if provided and column exists
        if ($userId !== null && static::hasColumn('user_id')) {
            $sql .= " WHERE user_id = :user_id";
            $params['user_id'] = $userId;
        }
        
        $result = Database::fetchOne($sql, $params);
        return $result['count'] ?? 0;
    }
    
    public static function markAsRead(int $id): int
    {
        return Database::query(
            "UPDATE " . self::$table . " SET status = 'read' WHERE id = ?",
            [$id]
        )->rowCount();
    }
    
    public static function markAsReplied(int $id): int
    {
        return Database::query(
            "UPDATE " . self::$table . " SET status = 'replied' WHERE id = ?",
            [$id]
        )->rowCount();
    }
}