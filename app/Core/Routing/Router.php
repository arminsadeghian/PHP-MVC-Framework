<?php

namespace App\Core\Routing;

use App\Core\Http\Request;
use App\Exceptions\ClassNotExistsException;
use App\Exceptions\MethodNotExistsException;

class Router
{
    public const BASE_CONTROLLER = '\App\Controllers\\';

    private Request $request;
    private array $routes;
    private mixed $currentRoute;

    public function __construct()
    {
        $this->request = new Request();
        $this->routes = Route::getAll();
        $this->currentRoute = $this->findRoute($this->request) ?? null;
        $this->runRouteMiddleware();
    }

    private function runRouteMiddleware()
    {
        $middleware = isset($this->currentRoute['middleware']) ?? null;
        if (is_object($middleware) || is_array($middleware)) {
            foreach ($middleware as $middlewareClass) {
                $middlewareObj = new $middlewareClass;
                $middlewareObj->handle();
            }
        }
    }

    private function findRoute(Request $request): mixed
    {
        foreach ($this->routes as $route) {
            if (!in_array($request->getMethod(), $route['methods'])) {
                return false;
            }
            if ($this->regexMatched($route)) {
                return $route;
            }

        }

        return null;
    }

    private function regexMatched(mixed $route): bool
    {
        global $request;
        $pattern = "/^" . str_replace(['/', '{', '}'], ['\/', '(?<', '>[-%\w]+)'], $route['uri']) . "$/";
        $result = preg_match($pattern, $this->request->getUri(), $matches);
        if (!$result) {
            return false;
        }
        foreach ($matches as $key => $value) {
            if (!is_int($key)) {
                $request->addRouteParam($key, $value);
            }
        }
        return true;
    }

    public function run()
    {
        # 405 : invalid request method
        if ($this->isInvalidRequestMethod($this->request)) {
            $this->dispatch405();
        }

        # 404 : uri not found
        if (is_null($this->currentRoute)) {
            $this->dispatch404();
        }

        $this->dispatch($this->currentRoute);
    }

    private function isInvalidRequestMethod(Request $request): bool
    {
        foreach ($this->routes as $route) {
            if (!in_array($request->getMethod(), $route['methods']) && $request->getUri() === $route['uri']) {
                return true;
            }
        }
        return false;
    }

    private function dispatch405()
    {
        header("HTTP/1.0 405 Method Not Allowed");
        view('errors.405');
        exit();
    }

    private function dispatch404()
    {
        header("HTTP/1.0 404 Not Found");
        view('errors.404');
        exit();
    }

    private function dispatch($route)
    {
        $action = $route['action'];

        # action : null
        if (is_null($action)) {
            return;
        }

        # action : closure
        if (is_callable($action)) {
            $action();
        }

        # action : Controller@Method
        if (is_string($action)) {
            $action = explode('@', $action);
        }

        # action : ['Controller','Method']
        if (is_array($action)) {
            $className = self::BASE_CONTROLLER . $action[0];
            $methodName = $action[1];
            if (!class_exists($className)) {
                throw new ClassNotExistsException("Class $className Not Exists!");
            }
            $controller = new $className();
            if (!method_exists($controller, $methodName)) {
                throw new MethodNotExistsException("Method $methodName Not Exists In Class $className!");
            }
            $controller->{$methodName}();
        }

    }

}
