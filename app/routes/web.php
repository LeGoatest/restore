<?php

use App\Controllers\HomeController;
use App\Controllers\ServicesController;
use App\Controllers\ContactController;
use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\DebugController;
use App\Controllers\CMSController;
use App\Controllers\CMSFrontendController;
use App\Controllers\PartialsController;
use App\Controllers\ApiController;

// Public routes (CMS-powered)
$router->get('/', CMSFrontendController::class, 'home');
$router->get('/services', CMSFrontendController::class, 'services');
$router->get('/contact', CMSFrontendController::class, 'contact');
$router->post('/contact', ContactController::class, 'submit');

// Service category partials for HTMX
$router->get('/partials/junk-removal', PartialsController::class, 'junkRemoval');
$router->get('/partials/surface-coatings', PartialsController::class, 'surfaceCoatings');
$router->get('/partials/landscaping', PartialsController::class, 'landscaping');
$router->get('/partials/cleaning', PartialsController::class, 'cleaning');

// Authentication routes
$router->get('/login', AuthController::class, 'loginForm');
$router->post('/login', AuthController::class, 'login');
$router->get('/logout', AuthController::class, 'logout');

// Debug routes (remove in production)
$router->get('/debug/post-test', DebugController::class, 'postTest');
$router->post('/debug/post-test', DebugController::class, 'postTest');

// Admin routes (protected)
$router->get('/admin', AdminController::class, 'dashboard');
$router->get('/admin/contacts', AdminController::class, 'contacts');
$router->get('/admin/quotes', AdminController::class, 'quotes');
$router->get('/admin/services', AdminController::class, 'services');
$router->get('/admin/cms', CMSController::class, 'dashboard');
$router->get('/admin/cms/system-builder', CMSController::class, 'systemBuilder');
$router->get('/admin/cms/blocks', CMSController::class, 'blocks');
$router->get('/admin/cms/blocks/create', CMSController::class, 'createBlock');
$router->post('/admin/cms/blocks/create', CMSController::class, 'createBlock');
$router->get('/admin/cms/blueprints', CMSController::class, 'blueprints');
$router->get('/admin/cms/blueprints/create', CMSController::class, 'createBlueprint');
$router->post('/admin/cms/blueprints/create', CMSController::class, 'createBlueprint');
$router->get('/admin/cms/blocks/edit', CMSController::class, 'editBlock');
$router->get('/admin/cms/blocks/builder', CMSController::class, 'blockBuilder');
$router->post('/admin/cms/blocks/update', CMSController::class, 'updateBlock');
$router->post('/admin/cms/blocks/delete', CMSController::class, 'deleteBlock');
$router->get('/admin/cms/blueprints/edit', CMSController::class, 'editBlueprint');
$router->post('/admin/cms/blueprints/update', CMSController::class, 'updateBlueprint');
$router->post('/admin/cms/blueprints/delete', CMSController::class, 'deleteBlueprint');
$router->get('/admin/cms/documents', CMSController::class, 'documents');
$router->get('/admin/cms/documents/create', CMSController::class, 'createDocument');
$router->post('/admin/cms/documents/create', CMSController::class, 'createDocument');
$router->get('/admin/cms/documents/edit', CMSController::class, 'editDocument');
$router->post('/admin/cms/documents/update', CMSController::class, 'updateDocument');
$router->post('/admin/cms/documents/delete', CMSController::class, 'deleteDocument');
$router->get('/admin/users', AdminController::class, 'users');
$router->get('/admin/settings', AdminController::class, 'settings');

// API routes
$router->post('/api/track', ApiController::class, 'track');