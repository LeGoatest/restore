<?php

require_once __DIR__ . '/../app/autoloader.php';

use App\Controllers\PageController;
use App\Controllers\SystemBuilderController;
use App\Controllers\DocumentController;
use App\Controllers\AuthController;
use App\Controllers\ApiController;

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

$router = new \Bramus\Router\Router();

$router->before('GET|POST', '/admin/.*', function() {
    if (!isset($_SESSION['user_id'])) {
        header('location: /login');
        exit();
    }
});

$router->get('/', function() {
    (new PageController())->dashboard();
});

$router->get('/editor', function() {
    (new PageController())->editor();
});

$router->get('/builder', function() {
    (new SystemBuilderController())->index();
});

$router->get('/builder/create-block', function() {
    (new SystemBuilderController())->createBlockForm();
});

$router->get('/builder/create-blueprint', function() {
    (new SystemBuilderController())->createBlueprintForm();
});

$router->get('/document/{slug}', function($slug) {
    (new DocumentController())->view($slug);
});

$router->get('/document/edit/{id}', function($id) {
    (new DocumentController())->editForm($id);
});

$router->get('/login', function() {
    (new AuthController())->showLoginForm();
});

$router->post('/login', function() {
    (new AuthController())->login();
});

$router->get('/logout', function() {
    (new AuthController())->logout();
});

$router->post('/api/{action}', function($action) {
    (new ApiController())->handle($action);
});

$router->run();
