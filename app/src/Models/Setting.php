<?php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Setting extends Model {
    protected static string $table = 'settings';
    protected static array $fillable = ['category', 'name', 'value'];
    
    /**
     * Get all settings by category
     */
    public static function getByCategory(string $category): array {
        $sql = "SELECT name, value FROM " . static::$table . " WHERE category = :category";
        $rows = Database::fetchAll($sql, ['category' => $category]);
        
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['name']] = $row['value'];
        }
        
        return $settings;
    }

    /**
     * Save multiple settings for a category
     */
    public static function saveCategory(string $category, array $settings): bool {
        try {
            foreach ($settings as $name => $value) {
                $data = [
                    'category' => $category,
                    'name' => $name,
                    'value' => $value
                ];
                
                $existing = Database::fetchOne(
                    "SELECT id FROM " . static::$table . " WHERE category = :category AND name = :name",
                    ['category' => $category, 'name' => $name]
                );
                
                if ($existing) {
                    static::update($existing['id'], $data);
                } else {
                    static::create($data);
                }
            }
            
            return true;
        } catch (\Exception $e) {
            error_log("Error saving settings: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get a specific setting value
     */
    public static function get(string $category, string $name, $default = null) {
        $row = Database::fetchOne(
            "SELECT value FROM " . static::$table . " WHERE category = :category AND name = :name",
            ['category' => $category, 'name' => $name]
        );
        
        return $row ? $row['value'] : $default;
    }

    /**
     * Save a single setting
     */
    public static function set(string $category, string $name, $value): bool {
        try {
            $data = [
                'category' => $category,
                'name' => $name,
                'value' => $value
            ];
            
            $existing = Database::fetchOne(
                "SELECT id FROM " . static::$table . " WHERE category = :category AND name = :name",
                ['category' => $category, 'name' => $name]
            );
            
            if ($existing) {
                static::update($existing['id'], $data);
            } else {
                static::create($data);
            }
            
            return true;
        } catch (\Exception $e) {
            error_log("Error saving setting: " . $e->getMessage());
            return false;
        }
    }
}