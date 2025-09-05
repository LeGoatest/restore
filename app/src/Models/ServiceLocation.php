<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;
use PDO;

class ServiceLocation extends Model
{
    protected static string $table = 'service_locations';
    protected static array $fillable = ['type', 'name'];
    /**
     * Get all service locations
     * @return array
     */
    public static function getAll(): array
    {
        $sql = "SELECT * FROM service_locations ORDER BY name ASC";
        return Database::fetchAll($sql);
    }

    /**
     * Get service locations by type
     * @param string $type
     * @return array
     */
    public static function getByType(string $type): array
    {
        $sql = "SELECT * FROM service_locations WHERE type = ? ORDER BY name ASC";
        return Database::fetchAll($sql, [$type]);
    }

    /**
     * Add a new service location
     * @param string $type
     * @param string $name
     * @return bool
     */
    public static function add(string $type, string $name): bool
    {
        try {
            Database::insert('service_locations', ['type' => $type, 'name' => $name]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Update a service location
     * @param int $id
     * @param string $type
     * @param string $name
     * @return bool
     */
    public static function updateLocation(int $id, string $type, string $name): bool
    {
        try {
            $data = ['type' => $type, 'name' => $name, 'updated_at' => date('Y-m-d H:i:s')];
            Database::update('service_locations', $data, 'id = :id', ['id' => $id]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Delete a service location
     * @param int $id
     * @return bool
     */
    public static function deleteLocation(int $id): bool
    {
        try {
            Database::delete('service_locations', 'id = :id', ['id' => $id]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get service locations formatted for schema.org
     * @return array
     */
    public static function getForSchema(): array
    {
        $locations = self::getAll();
        $schemaLocations = [];
        
        foreach ($locations as $location) {
            $schemaLocations[] = [
                '@type' => $location['type'],
                'name' => $location['name']
            ];
        }
        
        return $schemaLocations;
    }

    /**
     * Clear all locations and insert new ones
     * @param array $locations Array of ['type' => 'City', 'name' => 'Name'] arrays
     * @return bool
     */
    public static function replaceAll(array $locations): bool
    {
        try {
            $db = Database::getConnection();
            $db->beginTransaction();
            
            // Clear existing locations
            Database::query("DELETE FROM service_locations");
            
            // Insert new locations
            foreach ($locations as $location) {
                Database::insert('service_locations', $location);
            }
            
            $db->commit();
            return true;
        } catch (\Exception $e) {
            $db->rollBack();
            return false;
        }
    }

    /**
     * Get count of locations
     * @return int
     */
    public static function getCount(): int
    {
        $result = Database::fetchOne("SELECT COUNT(*) as count FROM service_locations");
        return (int)($result['count'] ?? 0);
    }
}