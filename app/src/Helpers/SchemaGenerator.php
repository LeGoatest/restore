<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Models\ServiceLocation;
use App\Models\Setting;

class SchemaGenerator
{
    /**
     * Generate LocalBusiness schema.org JSON-LD
     * @return array
     */
    public static function generateLocalBusiness(): array
    {
        $generalSettings = Setting::getByCategory('general');
        $serviceSettings = Setting::getByCategory('service');
        
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => $generalSettings['site_title'] ?? 'MyRestorePro',
            'description' => $serviceSettings['primary_services'] ?? '',
            'url' => $_SERVER['HTTP_HOST'] ?? 'localhost',
        ];

        // Add contact information
        if (!empty($generalSettings['phone'])) {
            $schema['telephone'] = $generalSettings['phone'];
        }
        
        if (!empty($generalSettings['email'])) {
            $schema['email'] = $generalSettings['email'];
        }

        // Add address
        if (!empty($generalSettings['address'])) {
            $schema['address'] = [
                '@type' => 'PostalAddress',
                'streetAddress' => $generalSettings['address'],
                'addressLocality' => $generalSettings['city'] ?? '',
                'addressRegion' => $generalSettings['state'] ?? 'FL',
                'postalCode' => $generalSettings['zip'] ?? '',
                'addressCountry' => 'US'
            ];
        }

        // Add geo coordinates
        if (!empty($generalSettings['latitude']) && !empty($generalSettings['longitude'])) {
            $schema['geo'] = [
                '@type' => 'GeoCoordinates',
                'latitude' => (float)$generalSettings['latitude'],
                'longitude' => (float)$generalSettings['longitude']
            ];
        }

        // Add service locations from database
        $serviceLocations = ServiceLocation::getForSchema();
        if (!empty($serviceLocations)) {
            $schema['serviceLocation'] = $serviceLocations;
        }

        // Add service area
        if (!empty($generalSettings['service_radius'])) {
            $schema['areaServed'] = [
                '@type' => 'GeoCircle',
                'geoMidpoint' => [
                    '@type' => 'GeoCoordinates',
                    'latitude' => (float)($generalSettings['latitude'] ?? 0),
                    'longitude' => (float)($generalSettings['longitude'] ?? 0)
                ],
                'geoRadius' => (int)$generalSettings['service_radius'] * 1609.34 // Convert miles to meters
            ];
        }

        // Add opening hours
        if (!empty($generalSettings['hours'])) {
            $openingHours = [];
            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            $dayMap = [
                'monday' => 'Mo',
                'tuesday' => 'Tu', 
                'wednesday' => 'We',
                'thursday' => 'Th',
                'friday' => 'Fr',
                'saturday' => 'Sa',
                'sunday' => 'Su'
            ];

            foreach ($days as $day) {
                if (!empty($generalSettings['hours'][$day]) && empty($generalSettings['hours'][$day]['closed'])) {
                    $open = $generalSettings['hours'][$day]['open'] ?? '09:00';
                    $close = $generalSettings['hours'][$day]['close'] ?? '17:00';
                    $openingHours[] = $dayMap[$day] . ' ' . $open . '-' . $close;
                }
            }

            if (!empty($openingHours)) {
                $schema['openingHours'] = $openingHours;
            }
        }

        // Add services offered
        if (!empty($serviceSettings['primary_services'])) {
            $services = array_filter(array_map('trim', explode("\n", $serviceSettings['primary_services'])));
            $schema['hasOfferCatalog'] = [
                '@type' => 'OfferCatalog',
                'name' => 'Services',
                'itemListElement' => array_map(function($service) {
                    return [
                        '@type' => 'Offer',
                        'itemOffered' => [
                            '@type' => 'Service',
                            'name' => $service
                        ]
                    ];
                }, $services)
            ];
        }

        return $schema;
    }

    /**
     * Generate and save schema.org JSON file
     * @return bool
     */
    public static function generateAndSave(): bool
    {
        $schema = self::generateLocalBusiness();
        $json = json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        
        return file_put_contents('public_html/schema.json', $json) !== false;
    }

    /**
     * Get schema.org JSON as string
     * @return string
     */
    public static function getJsonLd(): string
    {
        $schema = self::generateLocalBusiness();
        return json_encode($schema, JSON_UNESCAPED_SLASHES);
    }
}