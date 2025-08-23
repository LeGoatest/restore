<?php

declare(strict_types=1);

// Database setup script
require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Database;
use App\Core\Migration;

// Initialize database and migrations
Database::init();
Migration::init();

// Parse command line arguments
$command = $argv[1] ?? 'migrate';

switch ($command) {
    case 'migrate':
        echo "Running database migrations...\n";
        Migration::run();
        break;
        
    case 'reset':
        echo "Are you sure you want to reset the database? This will delete all data! (y/N): ";
        $handle = fopen("php://stdin", "r");
        $line = fgets($handle);
        fclose($handle);
        
        if (trim(strtolower($line)) === 'y') {
            Migration::reset();
            echo "Running migrations after reset...\n";
            Migration::run();
        } else {
            echo "Reset cancelled.\n";
        }
        break;
        
    case 'seed':
        echo "Seeding database with sample data...\n";
        seedDatabase();
        break;
        
    default:
        echo "Usage: php setup.php [migrate|reset|seed]\n";
        echo "  migrate - Run pending migrations\n";
        echo "  reset   - Reset database and run all migrations\n";
        echo "  seed    - Add sample data to database\n";
        exit(1);
}

function seedDatabase(): void
{
    // Add sample services
    $services = [
        ['name' => 'Home Cleanout', 'category' => 'junk-removal', 'description' => 'Complete home cleanout service', 'base_price' => 200.00],
        ['name' => 'Garage Cleanout', 'category' => 'junk-removal', 'description' => 'Garage and storage area cleaning', 'base_price' => 150.00],
        ['name' => 'Furniture Removal', 'category' => 'junk-removal', 'description' => 'Old furniture pickup and disposal', 'base_price' => 100.00],
        ['name' => 'Driveway Sealing', 'category' => 'surface-coatings', 'description' => 'Professional driveway sealing service', 'base_price' => 300.00],
        ['name' => 'Deck Staining', 'category' => 'surface-coatings', 'description' => 'Deck cleaning and staining', 'base_price' => 250.00],
        ['name' => 'Landscape Design', 'category' => 'landscaping', 'description' => 'Custom landscape design and installation', 'base_price' => 500.00],
        ['name' => 'Lawn Maintenance', 'category' => 'landscaping', 'description' => 'Regular lawn care and maintenance', 'base_price' => 80.00],
        ['name' => 'House Cleaning', 'category' => 'cleaning', 'description' => 'Professional house cleaning service', 'base_price' => 120.00],
        ['name' => 'Pressure Washing', 'category' => 'cleaning', 'description' => 'Exterior pressure washing service', 'base_price' => 180.00],
    ];

    foreach ($services as $service) {
        Database::insert('services', $service);
    }

    echo "✓ Sample services added\n";
    echo "Database seeding completed!\n";
}

echo "\nDatabase setup completed successfully!\n";