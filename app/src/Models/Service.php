<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Service extends Model
{
    protected static string $table = 'services';
    
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
    
    public static function all(): array
    {
        return Database::fetchAll("SELECT * FROM " . self::$table . " ORDER BY category, sort_order, name");
    }
    
    public static function count(): int
    {
        $result = Database::fetchOne("SELECT COUNT(*) as count FROM " . self::$table . " WHERE is_active = 1");
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