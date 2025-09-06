<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;
use App\DTOs\ServiceDTO;

class Service extends Model
{
    protected static string $table = 'services';
    protected static array $fillable = ['name', 'description', 'icon', 'category', 'sort_order', 'is_featured'];
    protected static array $allowHtml = ['description'];

    public static function find(int $id): ?ServiceDTO
    {
        $data = Database::fetchOne("SELECT * FROM " . static::$table . " WHERE id = ?", [$id]);
        return $data ? new ServiceDTO($data) : null;
    }

    public static function where(string $column, $value): array
    {
        $allData = Database::fetchAll(
            "SELECT * FROM " . self::$table . " WHERE {$column} = ? ORDER BY sort_order, name",
            [$value]
        );
        return array_map(fn($data) => new ServiceDTO($data), $allData);
    }
    
    public static function getByCategory(string $category): array
    {
        $allData = Database::fetchAll(
            "SELECT * FROM " . self::$table . " WHERE category = ? ORDER BY sort_order, name",
            [$category]
        );
        return array_map(fn($data) => new ServiceDTO($data), $allData);
    }
    
    public static function getActive(): array
    {
        // This method might need to be re-evaluated based on the new schema,
        // as `is_active` was removed. For now, we'll assume it means featured.
        return self::where('is_featured', 1);
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
        
        $allData = Database::fetchAll($sql, $params);
        return array_map(fn($data) => new ServiceDTO($data), $allData);
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