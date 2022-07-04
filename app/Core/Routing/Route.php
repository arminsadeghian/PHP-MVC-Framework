<?php

namespace App\Core\Routing;

class Route
{
    private static array $routes = [];

    public static function add($methods, string $uri, $action = null, $middleware = []): void
    {
        $methods = is_array($methods) ? $methods : [$methods];
        self::$routes[] = [
            'methods' => $methods,
            'uri' => $uri,
            'action' => $action,
            'middleware' => $middleware
        ];
    }

    public static function get(string $uri, $action = null, $middleware = []): void
    {
        self::add('get', $uri, $action, $middleware);
    }

    public static function post(string $uri, $action = null, $middleware = []): void
    {
        self::add('post', $uri, $action, $middleware);
    }

    public static function put(string $uri, $action = null, $middleware = []): void
    {
        self::add('put', $uri, $action, $middleware);
    }

    public static function delete(string $uri, $action = null, $middleware = []): void
    {
        self::add('delete', $uri, $action, $middleware);
    }

    public static function patch(string $uri, $action = null, $middleware = []): void
    {
        self::add('patch', $uri, $action, $middleware);
    }

    public static function options(string $uri, $action = null, $middleware = []): void
    {
        self::add('options', $uri, $action, $middleware);
    }

    public static function getAll(): array
    {
        return self::$routes;
    }
}
