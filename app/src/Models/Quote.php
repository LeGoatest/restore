<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Quote extends Model
{
    protected static string $table = 'quotes';
    
    public static function getByStatus(string $status): array
    {
        return Database::fetchAll(
            "SELECT * FROM " . self::$table . " WHERE status = ? ORDER BY created_at DESC",
            [$status]
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
    
    public static function getPending(): array
    {
        return self::getByStatus('pending');
    }
    
    public static function getApproved(): array
    {
        return self::getByStatus('approved');
    }
    
    public static function approve(int $id, float $estimatedAmount = null): int
    {
        $sql = "UPDATE " . self::$table . " SET status = 'approved'";
        $params = [$id];
        
        if ($estimatedAmount !== null) {
            $sql .= ", estimated_amount = ?";
            array_unshift($params, $estimatedAmount);
        }
        
        $sql .= " WHERE id = ?";
        
        return Database::query($sql, $params)->rowCount();
    }
    
    public static function reject(int $id, string $notes = null): int
    {
        $sql = "UPDATE " . self::$table . " SET status = 'rejected'";
        $params = [$id];
        
        if ($notes !== null) {
            $sql .= ", notes = ?";
            array_unshift($params, $notes);
        }
        
        $sql .= " WHERE id = ?";
        
        return Database::query($sql, $params)->rowCount();
    }
    
    public static function complete(int $id): int
    {
        return Database::query(
            "UPDATE " . self::$table . " SET status = 'completed' WHERE id = ?",
            [$id]
        )->rowCount();
    }
}