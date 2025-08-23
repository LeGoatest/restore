<?php

namespace Bramus\Router;

class Router
{
    private $routes = [];
    private $methods = [];
    private $patterns = [
        'all' => '.*',
        'num' => '[0-9]+',
        'alpha' => '[a-zA-Z]+',
        'alphanum' => '[a-zA-Z0-9]+',
        'hex' => '[a-fA-F0-9]+',
    ];
    private $errorCallback;

    public function __construct(callable $errorCallback = null)
    {
        $this->errorCallback = $errorCallback;
    }

    public function __call($method, $args)
    {
        list($route, $fn) = $args;
        $this->match(strtoupper($method), $route, $fn);
    }

    public function match($methods, $pattern, $fn)
    {
        $methods = is_array($methods) ? $methods : explode('|', $methods);
        foreach ($methods as $method) {
            $this->routes[$method][] = new Route($pattern, $fn);
        }
    }

    public function run(callable $callback = null)
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = rtrim($uri, '/');

        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $route) {
                if ($route->matches($uri)) {
                    return $route->dispatch();
                }
            }
        }

        if ($this->errorCallback) {
            call_user_func($this->errorCallback);
        } else {
            header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        }
    }

    public function before($methods, $pattern, $fn)
    {
        $this->match($methods, $pattern, $fn);
    }
}
