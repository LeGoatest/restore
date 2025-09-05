<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Quote extends Model
{
    protected static string $table = 'quotes';
    protected static array $fillable = ['name', 'email', 'phone', 'address', 'service_type', 'description', 'preferred_date', 'preferred_time', 'estimated_amount', 'status', 'notes'];
    protected static array $allowHtml = ['description', 'notes'];
    
    public static function getByStatus(string $status): array
    {
        return Database::fetchAll(
            "SELECT * FROM " . self::$table . " WHERE status = ? ORDER BY created_at DESC",
            [$status]
        );
    }
    
    public static function getRecent(int $limit = 10): array
    {
        return Database::fetchAll(
            "SELECT * FROM " . self::$table . " ORDER BY created_at DESC LIMIT ?",
            [$limit]
        );
    }
    
    public static function all(?int $userId = null): array
    {
        $sql = "SELECT * FROM " . self::$table;
        $params = [];
        
        // Add user_id filter if provided and column exists
        if ($userId !== null && static::hasColumn('user_id')) {
            $sql .= " WHERE user_id = :user_id";
            $params['user_id'] = $userId;
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        return Database::fetchAll($sql, $params);
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
    
    public static function getPending(): array
    {
        return self::getByStatus('pending');
    }
    
    public static function markAsApproved(int $id): int
    {
        return Database::query(
            "UPDATE " . self::$table . " SET status = 'approved' WHERE id = ?",
            [$id]
        )->rowCount();
    }
    
    public static function markAsRejected(int $id): int
    {
        return Database::query(
            "UPDATE " . self::$table . " SET status = 'rejected' WHERE id = ?",
            [$id]
        )->rowCount();
    }
}