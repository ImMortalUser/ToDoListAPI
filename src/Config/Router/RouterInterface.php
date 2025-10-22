<?php

namespace App\Config\Router;

interface RouterInterface {
    public function addRoute(string $method, string $uri, callable $action): void;
    public function dispatch(string $httpMethod, string $uri): mixed;
}