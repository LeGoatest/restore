<?php

namespace Tests;

use App\Core\Database;
use App\Core\Migration;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Set testing flag
        Database::$is_testing = true;

        // Use an in-memory SQLite database for testing
        Database::init(':memory:');

        // Run all migrations
        Migration::init();
        Migration::run();

        // Seed the database with sample data
        $this->seedDatabase();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        \App\Core\Database::close();
    }

    private function seedDatabase(): void
    {
        // This is a simplified version of the seed logic from setup.php
        // In a real application, you might want to share this logic.
        $services = [
            ['name' => 'Home Cleanout', 'category' => 'junk-removal', 'description' => 'Complete home cleanout service', 'base_price' => 200.00],
            ['name' => 'Garage Cleanout', 'category' => 'junk-removal', 'description' => 'Garage and storage area cleaning', 'base_price' => 150.00],
        ];

        foreach ($services as $service) {
            Database::insert('services', $service);
        }
    }
}
