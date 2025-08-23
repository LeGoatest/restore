<?php

declare(strict_types=1);

namespace App\Core;

abstract class Model
{
    protected static string $table;
    protected static string $primaryKey = 'id';
    
    public static function all(): array
    {
        return Database::fetchAll("SELECT * FROM " . static::$table . " ORDER BY " . static::$primaryKey);
    }
    
    public static function find(int $id): ?array
    {
        return Database::fetchOne(
            "SELECT * FROM " . static::$table . " WHERE " . static::$primaryKey . " = ?",
            [$id]
        );
    }
    
    public static function where(string $column, mixed $value): array
    {
        return Database::fetchAll(
            "SELECT * FROM " . static::$table . " WHERE {$column} = ?",
            [$value]
        );
    }
    
    public static function create(array $data): int
    {
        // Add timestamps if columns exist
        if (static::hasColumn('created_at')) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        if (static::hasColumn('updated_at')) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        
        return Database::insert(static::$table, $data);
    }
    
    public static function update(int $id, array $data): int
    {
        // Add updated timestamp if column exists
        if (static::hasColumn('updated_at')) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        
        return Database::update(
            static::$table,
            $data,
            static::$primaryKey . " = :id",
            ['id' => $id]
        );
    }
    
    public static function delete(int $id): int
    {
        return Database::delete(
            static::$table,
            static::$primaryKey . " = ?",
            [$id]
        );
    }
    
    public static function count(): int
    {
        $result = Database::fetchOne("SELECT COUNT(*) as count FROM " . static::$table);
        return (int) $result['count'];
    }
    
    private static function hasColumn(string $column): bool
    {
        $columns = Database::fetchAll("PRAGMA table_info(" . static::$table . ")");
        foreach ($columns as $col) {
            if ($col['name'] === $column) {
                return true;
            }
        }
        return false;
    }
}