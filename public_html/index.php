<?php

declare(strict_types=1);

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Bootstrap the application
require_once __DIR__ . '/../app/vendor/autoload.php';

use App\Core\Router;
use App\Core\Database;

// Initialize core services
Database::init();

// Initialize router
$router = new Router();

// Load routes
require_once __DIR__ . '/../app/routes/web.php';

// Get current request
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

try {
    // Resolve route
    $route = $router->resolve($method, $path);
    
    // Instantiate controller and call method
    $controllerClass = $route['controller'];
    $controllerMethod = $route['method'];
    
    $controller = new $controllerClass();
    $response = $controller->$controllerMethod();
    
    // Output response
    echo $response;
    
} catch (Exception $e) {
    // Handle 404 and other errors
    http_response_code($e->getCode() === 404 ? 404 : 500);
    
    if ($e->getCode() === 404) {
        echo '<h1>404 - Page Not Found</h1>';
    } else {
        echo '<h1>500 - Internal Server Error</h1>';
        echo '<pre>' . $e->getMessage() . '</pre>';
        echo '<pre>' . $e->getTraceAsString() . '</pre>';
    }
}