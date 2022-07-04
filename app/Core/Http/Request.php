<?php

namespace App\Core\Http;

class Request
{
    private array $params;
    private $routeParams;
    private string $method;
    private mixed $agent;
    private mixed $ipAddress;
    private string|bool $uri;

    public function __construct()
    {
        $this->params = $_REQUEST;
        $this->method = strtolower($_SERVER['REQUEST_METHOD']);
        $this->agent = $_SERVER['HTTP_USER_AGENT'];
        $this->ipAddress = $_SERVER['REMOTE_ADDR'];
        $this->uri = strtok($_SERVER['REQUEST_URI'], '?');
    }

    public function __get(string $name)
    {
        return $this->params[$name] ?? null;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getAgent(): mixed
    {
        return $this->agent;
    }

    public function getIpAddress(): mixed
    {
        return $this->ipAddress;
    }

    public function getUri(): bool|string
    {
        return $this->uri;
    }

    public function input(string $key): mixed
    {
        return $this->params[$key] ?? null;
    }

    public function isset(string $key): bool
    {
        return isset($this->params[$key]);
    }

    public function addRouteParam(string $key, mixed $value)
    {
        $this->routeParams[$key] = $value;
    }

    public function getRouteParam(string $key)
    {
        return $this->routeParams[$key];
    }

    public function getRouteParams()
    {
        return $this->routeParams;
    }

}
