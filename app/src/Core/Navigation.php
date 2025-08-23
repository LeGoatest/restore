<?php

declare(strict_types=1);

namespace App\Core;

class Navigation
{
    public static function getNavItems(): array
    {
        return [
            ['url' => '/', 'text' => 'Home', 'view' => 'home'],
            ['url' => '/services', 'text' => 'Services', 'view' => 'services'],
            ['url' => '/contact', 'text' => 'Contact', 'view' => 'contact'],
            ['url' => '/login', 'text' => 'Login', 'view' => 'login']
        ];
    }

    public static function getAdminNavItems(): array
    {
        return [
            ['url' => '/admin', 'text' => 'Dashboard', 'view' => 'admin'],
            ['url' => '/admin/contacts', 'text' => 'Contacts', 'view' => 'contacts'],
            ['url' => '/admin/quotes', 'text' => 'Quotes', 'view' => 'quotes'],
            ['url' => '/admin/services', 'text' => 'Services', 'view' => 'services'],
            ['url' => '/admin/cms', 'text' => 'CMS', 'view' => 'cms'],
            ['url' => '/admin/users', 'text' => 'Users', 'view' => 'users'],
            ['url' => '/admin/settings', 'text' => 'Settings', 'view' => 'settings']
        ];
    }

    public static function isCurrentPage(string $view): bool
    {
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Map paths to views
        $pathToView = [
            '/' => 'home',
            '/services' => 'services',
            '/contact' => 'contact',
            '/login' => 'login',
            '/admin' => 'admin',
            '/admin/contacts' => 'contacts',
            '/admin/quotes' => 'quotes',
            '/admin/services' => 'services',
            '/admin/cms' => 'cms',
            '/admin/users' => 'users',
            '/admin/settings' => 'settings'
        ];

        return ($pathToView[$currentPath] ?? '') === $view;
    }
}