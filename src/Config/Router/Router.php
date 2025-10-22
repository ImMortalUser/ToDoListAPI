<?php

namespace App\Config\Router;

class Router implements RouterInterface
{
    private array $routes = [];

    public function addRoute(string $method, string $uri, callable $action): void
    {
        $this->routes[] = compact('method', 'uri', 'action');
    }

    public function dispatch(string $httpMethod, string $uri): mixed
    {
        foreach ($this->routes as $route) {
            if ($route['method'] === $httpMethod && $route['uri'] === $uri) {
                return call_user_func($route['action']);
            }
        }

        http_response_code(404);
        return ['error' => 'Route not found'];
    }
}