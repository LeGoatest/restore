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
        $force = in_array('-y', $argv) || in_array('--force', $argv);
        
        if ($force) {
            Migration::reset();
            echo "Running migrations after reset...\n";
            Migration::run();
        } else {
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
    // Seed settings
    $settings = [
        ['category' => 'home_page', 'name' => 'title', 'value' => 'MyRestorePro - Professinal Restoration Services in Central Florida'],
        ['category' => 'home_page', 'name' => 'meta_description', 'value' => 'Hassle-free , Professional, reliable service with free estimates. Call (727) 692-8167 today!'],
        ['category' => 'home_page', 'name' => 'hero_title', 'value' => 'Professional Restoration Services'],
        ['category' => 'home_page', 'name' => 'hero_subtitle', 'value' => 'Hassle-Free, Reliable Professinal Restoration Services in Central Florida'],
        ['category' => 'home_page', 'name' => 'hero_description', 'value' => 'From property cleanouts, To home remodels, we handle it all.'],
        ['category' => 'business', 'name' => 'phone', 'value' => '(727) 692-8167'],
        ['category' => 'business', 'name' => 'email', 'value' => 'info@myrestorepro.com'],
        ['category' => 'business', 'name' => 'hours_weekdays', 'value' => 'Mo-Sa: 7:00 AM - 6:00 PM'],
    ];
    foreach ($settings as $setting) {
        Database::insert('settings', $setting);
    }
    echo "✓ Settings seeded\n";

    // Seed services
    $services = [
        ['icon' => 'mdi--home', 'name' => 'Residential Cleanouts', 'description' => 'Complete home cleanouts from attic to basement', 'is_featured' => 1],
        ['icon' => 'mdi--office-building', 'name' => 'Commercial Services', 'description' => 'Office cleanouts and commercial junk removal', 'is_featured' => 1],
        ['icon' => 'mdi--garage', 'name' => 'Garage Cleanouts', 'description' => 'Reclaim your garage space with our cleanout service', 'is_featured' => 1],
        ['icon' => 'mdi--storage', 'name' => 'Storage Unit Cleanouts', 'description' => 'Complete storage unit clearing and cleanout', 'is_featured' => 1],
        ['icon' => 'mdi--hot-tub', 'name' => 'Hot Tub Removal', 'description' => 'Safe and professional hot tub removal service', 'is_featured' => 1],
        ['icon' => 'mdi--account-group', 'name' => 'Estate Cleanouts', 'description' => 'Compassionate estate and senior home cleanouts', 'is_featured' => 1],
    ];
    foreach ($services as $service) {
        Database::insert('services', $service);
    }
    echo "✓ Services seeded\n";

    // Seed site benefits
    $benefits = [
        ['icon' => 'icon-[streamline-ultimate--delivery-truck-clock-bold]', 'title' => 'Same-day service', 'description' => 'Book today. We will call before we arrive'],
        ['icon' => 'icon-[streamline-ultimate--shipping-logistic-free-shipping-delivery-truck-bold]', 'title' => 'Get a free estimate', 'description' => 'Transparent pricing with no hidden fees'],
        ['icon' => 'icon-[streamline-ultimate--workflow-teamwork-user-high-five-bold]', 'title' => 'Friendly, professional teams', 'description' => 'Licensed, insured, and reliable team'],
    ];
    foreach ($benefits as $benefit) {
        Database::insert('site_benefits', $benefit);
    }
    echo "✓ Site benefits seeded\n";

    echo "Database seeding completed!\n";
}

echo "\nDatabase setup completed successfully!\n";