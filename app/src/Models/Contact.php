<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Contact extends Model
{
    protected static string $table = 'contacts';
    
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
    
    public static function all(): array
    {
        return Database::fetchAll("SELECT * FROM " . self::$table . " ORDER BY created_at DESC");
    }
    
    public static function count(): int
    {
        $result = Database::fetchOne("SELECT COUNT(*) as count FROM " . self::$table);
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