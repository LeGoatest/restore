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
                // Convert arrays to JSON, handle other types appropriately
                if (is_array($value)) {
                    $value = json_encode($value);
                } elseif (is_bool($value)) {
                    $value = $value ? '1' : '0';
                } else {
                    $value = (string) $value;
                }
                
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
        
        if (!$row) {
            return $default;
        }
        
        $value = $row['value'];
        
        // Try to decode JSON, return as-is if not valid JSON
        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }
        
        return $value;
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

    /**
     * Generate JSON-LD structured data for local business
     */
    public static function getLocalBusinessSchema(): array {
        $general = static::getByCategory('general');
        $business = static::getByCategory('business');
        $hours = static::getByCategory('hours');
        
        // Build opening hours array
        $openingHours = [];
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $dayAbbr = ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'];
        
        foreach ($days as $index => $day) {
            $dayData = json_decode($hours[$day] ?? '{}', true);
            if (empty($dayData['closed'])) {
                $open = $dayData['open'] ?? '07:00';
                $close = $dayData['close'] ?? '18:00';
                $openingHours[] = $dayAbbr[$index] . ' ' . $open . '-' . $close;
            } else {
                $openingHours[] = $dayAbbr[$index] . ' Closed';
            }
        }
        
        // Build the schema
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => $business['name'] ?? $general['site_title'] ?? 'MyRestorePro',
            'description' => $business['description'] ?? 'Professional Restoration services',
            'telephone' => $business['phone'] ?? '',
            'email' => $business['email'] ?? $general['admin_email'] ?? '',
        ];
        
        // Add address if available
        if (!empty($business['city']) || !empty($business['address'])) {
            $schema['address'] = [
                '@type' => 'PostalAddress',
                'addressLocality' => $business['city'] ?? '',
                'addressRegion' => $business['state'] ?? 'FL',
                'addressCountry' => 'US'
            ];
            
            if (!empty($business['address'])) {
                $schema['address']['streetAddress'] = $business['address'];
            }
            
            if (!empty($business['zip'])) {
                $schema['address']['postalCode'] = $business['zip'];
            }
        }
        
        // Add coordinates if available
        if (!empty($business['latitude']) && !empty($business['longitude'])) {
            $schema['geo'] = [
                '@type' => 'GeoCoordinates',
                'latitude' => (float) $business['latitude'],
                'longitude' => (float) $business['longitude']
            ];
            
            // Add service area if radius is specified
            if (!empty($business['service_radius'])) {
                $schema['serviceArea'] = [
                    '@type' => 'GeoCircle',
                    'geoMidpoint' => [
                        '@type' => 'GeoCoordinates',
                        'latitude' => (float) $business['latitude'],
                        'longitude' => (float) $business['longitude']
                    ],
                    'geoRadius' => (int) $business['service_radius'] * 1609 // Convert miles to meters
                ];
            }
        }
        
        // Add opening hours
        if (!empty($openingHours)) {
            $schema['openingHours'] = $openingHours;
        }
        
        return $schema;
    }

    /**
     * Get JSON-LD structured data as JSON string
     */
    public static function getLocalBusinessSchemaJson(): string {
        return json_encode(static::getLocalBusinessSchema(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}