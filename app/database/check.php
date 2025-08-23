<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Database;

try {
    // Initialize database
    Database::init();
    
    echo "✓ Database connection successful\n";
    
    // Check if tables exist
    $tables = ['contacts', 'quotes', 'services', 'migrations'];
    
    foreach ($tables as $table) {
        $result = Database::fetchOne("SELECT name FROM sqlite_master WHERE type='table' AND name=?", [$table]);
        if ($result) {
            echo "✓ Table '{$table}' exists\n";
        } else {
            echo "✗ Table '{$table}' missing\n";
        }
    }
    
    // Show record counts
    echo "\nRecord counts:\n";
    foreach (['contacts', 'quotes', 'services'] as $table) {
        try {
            $count = Database::fetchOne("SELECT COUNT(*) as count FROM {$table}");
            echo "  {$table}: {$count['count']} records\n";
        } catch (Exception $e) {
            echo "  {$table}: Error - {$e->getMessage()}\n";
        }
    }
    
    echo "\nDatabase location: " . __DIR__ . '/app.db' . "\n";
    
} catch (Exception $e) {
    echo "✗ Database error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\nDatabase check completed successfully!\n";