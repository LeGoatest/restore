<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Service extends Model
{
    protected static string $table = 'services';
    protected static array $fillable = ['name', 'description', 'category', 'price', 'sort_order', 'is_active'];
    protected static array $allowHtml = ['description'];
    
    public static function getByCategory(string $category): array
    {
        return Database::fetchAll(
            "SELECT * FROM " . self::$table . " WHERE category = ? AND is_active = 1 ORDER BY sort_order, name",
            [$category]
        );
    }
    
    public static function getActive(): array
    {
        return Database::fetchAll(
            "SELECT * FROM " . self::$table . " WHERE is_active = 1 ORDER BY category, sort_order, name"
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
        
        $sql .= " ORDER BY category, sort_order, name";
        
        return Database::fetchAll($sql, $params);
    }
    
    public static function count(?int $userId = null): int
    {
        $sql = "SELECT COUNT(*) as count FROM " . self::$table . " WHERE is_active = 1";
        $params = [];
        
        // Add user_id filter if provided and column exists
        if ($userId !== null && static::hasColumn('user_id')) {
            $sql .= " AND user_id = :user_id";
            $params['user_id'] = $userId;
        }
        
        $result = Database::fetchOne($sql, $params);
        return $result['count'] ?? 0;
    }
    
    public static function getCategories(): array
    {
        return Database::fetchAll(
            "SELECT DISTINCT category FROM " . self::$table . " WHERE is_active = 1 ORDER BY category"
        );
    }
    
    public static function activate(int $id): int
    {
        return Database::query(
            "UPDATE " . self::$table . " SET is_active = 1 WHERE id = ?",
            [$id]
        )->rowCount();
    }
    
    public static function deactivate(int $id): int
    {
        return Database::query(
            "UPDATE " . self::$table . " SET is_active = 0 WHERE id = ?",
            [$id]
        )->rowCount();
    }
}