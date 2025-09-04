<?php

use App\Controllers\HomeController;
use App\Controllers\ServicesController;
use App\Controllers\ContactController;
use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\DebugController;

use App\Controllers\PartialsController;
use App\Controllers\ApiController;

// Public routes
$router->get('/', HomeController::class, 'index');
$router->get('/services', ServicesController::class, 'index');
$router->get('/contact', ContactController::class, 'index');
$router->post('/contact', ContactController::class, 'submit');
$router->get('/quote', ContactController::class, 'quote');
$router->post('/quote', ContactController::class, 'submitQuote');

// Service category partials for HTMX
$router->get('/partials/junk-removal', PartialsController::class, 'junkRemoval');
$router->get('/partials/surface-coatings', PartialsController::class, 'surfaceCoatings');
$router->get('/partials/landscaping', PartialsController::class, 'landscaping');
$router->get('/partials/cleaning', PartialsController::class, 'cleaning');

// Authentication routes
$router->get('/login', AuthController::class, 'loginForm');
$router->post('/login/request', AuthController::class, 'requestLogin');
$router->get('/login/verify/{token}', AuthController::class, 'verifyMagicLink');
$router->get('/logout', AuthController::class, 'logout');

// Debug routes (remove in production)
$router->get('/debug/post-test', DebugController::class, 'postTest');
$router->post('/debug/post-test', DebugController::class, 'postTest');

// Admin routes (protected)
$router->get('/admin', AdminController::class, 'dashboard');
$router->get('/admin/contacts', AdminController::class, 'contacts');
$router->get('/admin/quotes', AdminController::class, 'quotes');
$router->get('/admin/services', AdminController::class, 'services');
$router->get('/admin/hero', AdminController::class, 'hero');
$router->get('/admin/users', AdminController::class, 'users');
$router->get('/admin/settings', AdminController::class, 'settings');
$router->post('/admin/settings/save', AdminController::class, 'saveSettings');
$router->post('/admin/hero/save', AdminController::class, 'saveHero');

// Tracking route (using GET to avoid POST restrictions)
$router->get('/analytics/track', ApiController::class, 'track');