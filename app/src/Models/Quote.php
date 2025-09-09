<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;
use App\DTOs\QuoteDTO;

class Quote extends Model
{
    protected static string $table = 'quotes';
    protected static array $fillable = ['name', 'email', 'phone', 'address', 'service_type', 'description', 'preferred_date', 'preferred_time', 'estimated_amount', 'status', 'notes'];
    protected static array $allowHtml = ['description', 'notes'];

    public static function find(int $id): ?QuoteDTO
    {
        $data = parent::find($id);
        return $data ? new QuoteDTO($data) : null;
    }
    
    public static function getByStatus(string $status): array
    {
        $allData = Database::fetchAll(
            "SELECT * FROM " . self::$table . " WHERE status = ? ORDER BY created_at DESC",
            [$status]
        );
        return array_map(fn($data) => new QuoteDTO($data), $allData);
    }
    
    public static function getRecent(int $limit = 10): array
    {
        $allData = Database::fetchAll(
            "SELECT * FROM " . self::$table . " ORDER BY created_at DESC LIMIT ?",
            [$limit]
        );
        return array_map(fn($data) => new QuoteDTO($data), $allData);
    }
    
    public static function all(?int $userId = null): array
    {
        $allData = parent::all($userId);
        return array_map(fn($data) => new QuoteDTO($data), $allData);
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