<?php
require 'vendor/autoload.php';

use App\Core\Router;

// Initialize router
$router = new Router();

// Load routes
require 'routes/web.php';

// Test the route resolution
try {
    $route = $router->resolve('POST', '/api/track');
    echo "Route found:\n";
    echo "Controller: " . $route['controller'] . "\n";
    echo "Method: " . $route['method'] . "\n";
    if (isset($route['params'])) {
        echo "Params: " . print_r($route['params'], true) . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>