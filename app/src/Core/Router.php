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

    public function delete(string $path, string $controller, string $method): void
    {
        $this->routes['DELETE'][$path] = ['controller' => $controller, 'method' => $method];
    }

    public function resolve(string $method, string $path): array
    {
        // First try exact match
        $route = $this->routes[$method][$path] ?? null;
        
        if ($route) {
            return $route;
        }
        
        // Try parameterized routes
        foreach ($this->routes[$method] ?? [] as $routePath => $routeData) {
            if (strpos($routePath, '{') !== false) {
                $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $routePath);
                $pattern = '#^' . $pattern . '$#';
                
                if (preg_match($pattern, $path, $matches)) {
                    // Extract parameters
                    array_shift($matches); // Remove full match
                    $routeData['params'] = $matches;
                    return $routeData;
                }
            }
        }
        
        throw new \Exception('Route not found', 404);
    }
}