<?php
declare(strict_types=1);

namespace src\Config;

use Closure;
use ReflectionFunction;
use ReflectionMethod;

class Router
{
    private array $routes = [];

    public function addRoute(string $method, string $uri, callable $action): void
    {
        if ($uri !== '/' && str_ends_with($uri, '/')) {
            $uri = rtrim($uri, '/');
        }
        $pattern = preg_replace('#\{[^/]+\}#', '([^/]+)', $uri);
        $pattern = '#^' . $pattern . '$#';
        preg_match_all('#\{([^/]+)\}#', $uri, $matches);
        $paramNames = $matches[1] ?? [];
        $this->routes[] = [
            'method' => strtoupper($method),
            'uri' => $uri,
            'pattern' => $pattern,
            'paramNames' => $paramNames,
            'action' => $action,
        ];
    }

    public function dispatch(string $httpMethod, string $uri): mixed
    {
        if ($uri !== '/' && str_ends_with($uri, '/')) {
            $uri = rtrim($uri, '/');
        }
        foreach ($this->routes as $route) {
            if ($route['method'] !== strtoupper($httpMethod)) {
                continue;
            }
            if (preg_match($route['pattern'], $uri, $matches)) {
                array_shift($matches);
                $params = [];
                foreach ($route['paramNames'] as $i => $name) {
                    $params[$name] = $matches[$i] ?? null;
                }
                $action = $route['action'];
                try {
                    if (is_array($action) && count($action) >= 2) {
                        $ref = new ReflectionMethod($action[0], $action[1]);
                    } elseif ($action instanceof Closure) {
                        $ref = new ReflectionFunction($action);
                    } elseif (is_string($action) && str_contains($action, '::')) {
                        [$class, $method] = explode('::', $action, 2);
                        $ref = new ReflectionMethod($class, $method);
                    } else {
                        $ref = new ReflectionFunction(Closure::fromCallable($action));
                    }
                    $numParams = $ref->getNumberOfParameters();
                } catch (\Throwable $e) {
                    $numParams = 1;
                }
                if ($numParams > 0) {
                    return call_user_func($action, $params);
                }
                return call_user_func($action);
            }
        }
        http_response_code(404);
        return ['error' => 'Route not found'];
    }
}
