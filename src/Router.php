<?php

declare(strict_types=1);

namespace Src;

class Router
{
    public array $routes;

    public function __construct()
    {
        
    }

    public function register(string $route, callable $action): self
    {
        $this->routes[$route] = $action;

        return $this;
    }

    public function resolve(string $requestUri)
    {
        $route = explode("?", $requestUri)[0];

        dd($route);
    }
}
