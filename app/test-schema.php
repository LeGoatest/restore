<?php
require_once 'vendor/autoload.php';

use App\Core\Database;
use App\Models\Setting;

// Initialize database
Database::init();

echo "Testing Schema Generation\n";
echo "========================\n\n";

// Test the schema generation
$schema = Setting::getLocalBusinessSchema();
echo "Generated Schema:\n";
echo json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

echo "\n\nJSON-LD for HTML:\n";
echo Setting::getLocalBusinessSchemaJson();