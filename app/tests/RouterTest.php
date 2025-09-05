<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Core\Router;

class RouterTest extends TestCase
{
    private $router;

    protected function setUp(): void
    {
        $this->router = new Router();
    }

    public function testGetRoute()
    {
        $this->router->get('/test', 'TestController', 'index');
        $route = $this->router->resolve('GET', '/test');
        $this->assertEquals(['controller' => 'TestController', 'method' => 'index'], $route);
    }

    public function testPostRoute()
    {
        $this->router->post('/test', 'TestController', 'store');
        $route = $this->router->resolve('POST', '/test');
        $this->assertEquals(['controller' => 'TestController', 'method' => 'store'], $route);
    }

    public function testDeleteRoute()
    {
        $this->router->delete('/test', 'TestController', 'destroy');
        $route = $this->router->resolve('DELETE', '/test');
        $this->assertEquals(['controller' => 'TestController', 'method' => 'destroy'], $route);
    }

    public function testRouteNotFound()
    {
        $this->expectException(\Exception::class);
        $this->router->resolve('GET', '/non-existent-route');
    }
}
