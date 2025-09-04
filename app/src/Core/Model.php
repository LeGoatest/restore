<?php

declare(strict_types=1);

namespace App\Core;

abstract class Model
{
    protected static string $table;
    protected static string $primaryKey = 'id';
    protected static array $fillable = [];
    protected static array $allowHtml = [];
    
    public static function all(?int $userId = null): array
    {
        $sql = "SELECT * FROM " . static::$table;
        $params = [];
        
        // Add user_id check if provided (IDOR protection)
        if ($userId !== null && static::hasColumn('user_id')) {
            $sql .= " WHERE user_id = :user_id";
            $params['user_id'] = $userId;
        }
        
        $sql .= " ORDER BY " . static::$primaryKey;
        return Database::fetchAll($sql, $params);
    }
    
    public static function find(int $id, ?int $userId = null): ?array
    {
        $sql = "SELECT * FROM " . static::$table . " WHERE " . static::$primaryKey . " = :id";
        $params = ['id' => $id];
        
        // Add user_id check if provided (IDOR protection)
        if ($userId !== null && static::hasColumn('user_id')) {
            $sql .= " AND user_id = :user_id";
            $params['user_id'] = $userId;
        }
        
        return Database::fetchOne($sql, $params);
    }
    
    public static function where(string $column, mixed $value, ?int $userId = null): array
    {
        // Prevent SQL injection in column name by validating against actual columns
        if (!static::hasColumn($column)) {
            throw new \InvalidArgumentException("Invalid column name: {$column}");
        }
        
        $sql = "SELECT * FROM " . static::$table . " WHERE {$column} = :value";
        $params = ['value' => $value];
        
        // Add user_id check if provided (IDOR protection)
        if ($userId !== null && static::hasColumn('user_id')) {
            $sql .= " AND user_id = :user_id";
            $params['user_id'] = $userId;
        }
        
        return Database::fetchAll($sql, $params);
    }
    
    public static function create(array $data): int
    {
        // Filter and sanitize input data
        $data = static::filterData($data);
        
        // Add timestamps if columns exist
        if (static::hasColumn('created_at')) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        if (static::hasColumn('updated_at')) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        
        // Add user_id if available from session (IDOR protection)
        if (static::hasColumn('user_id') && isset($_SESSION['user_id'])) {
            $data['user_id'] = $_SESSION['user_id'];
        }
        
        return Database::insert(static::$table, $data);
    }
    
    public static function update(int $id, array $data, ?int $userId = null): int
    {
        // Filter and sanitize input data
        $data = static::filterData($data);
        
        // Add updated timestamp if column exists
        if (static::hasColumn('updated_at')) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        
        $where = static::$primaryKey . " = :id";
        $whereParams = ['id' => $id];
        
        // Add user_id check if provided (IDOR protection)
        if ($userId !== null && static::hasColumn('user_id')) {
            $where .= " AND user_id = :user_id";
            $whereParams['user_id'] = $userId;
        }
        
        return Database::update(static::$table, $data, $where, $whereParams);
    }
    
    public static function delete(int $id, ?int $userId = null): int
    {
        $where = static::$primaryKey . " = :id";
        $params = ['id' => $id];
        
        // Add user_id check if provided (IDOR protection)
        if ($userId !== null && static::hasColumn('user_id')) {
            $where .= " AND user_id = :user_id";
            $params['user_id'] = $userId;
        }
        
        return Database::delete(static::$table, $where, $params);
    }
    
    public static function count(?int $userId = null): int
    {
        $sql = "SELECT COUNT(*) as count FROM " . static::$table;
        $params = [];
        
        // Add user_id check if provided (IDOR protection)
        if ($userId !== null && static::hasColumn('user_id')) {
            $sql .= " WHERE user_id = :user_id";
            $params['user_id'] = $userId;
        }
        
        $result = Database::fetchOne($sql, $params);
        return (int) $result['count'];
    }
    
    protected static function filterData(array $data): array
    {
        // Remove any non-fillable fields
        if (!empty(static::$fillable)) {
            $data = array_intersect_key($data, array_flip(static::$fillable));
        }
        
        // Basic sanitization for strings
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                // Remove any null bytes
                $data[$key] = str_replace("\0", '', $value);
                
                // Strip tags if not explicitly allowed
                if (!in_array($key, static::$allowHtml)) {
                    $data[$key] = strip_tags($value);
                } else {
                    // For allowed HTML fields, still sanitize to prevent XSS
                    $data[$key] = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                }
            }
        }
        
        return $data;
    }
    
    protected static function hasColumn(string $column): bool
    {
        static $columnCache = [];
        
        // Cache the columns to prevent repeated queries
        if (!isset($columnCache[static::$table])) {
            $columnCache[static::$table] = [];
            $columns = Database::fetchAll("PRAGMA table_info(" . static::$table . ")");
            foreach ($columns as $col) {
                $columnCache[static::$table][$col['name']] = true;
            }
        }
        
        return isset($columnCache[static::$table][$column]);
    }
}