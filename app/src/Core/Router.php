<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, string $controller, string $method): void
    {
        $this->routes['GET'][$path] = ['controller' => $controller, 'method' => $method];
    }

    public function post(string $path, string $controller, string $method): void
    {
        $this->routes['POST'][$path] = ['controller' => $controller, 'method' => $method];
    }

    public function resolve(string $method, string $path): array
    {
        $route = $this->routes[$method][$path] ?? null;
        
        if (!$route) {
            throw new \Exception('Route not found', 404);
        }

        return $route;
    }
}