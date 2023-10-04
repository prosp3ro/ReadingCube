<?php

declare(strict_types=1);

namespace Src;

use Src\Exceptions\RouteNotFoundException;

class Router
{
    public array $routes;

    public function __construct()
    {
    }

    public function register(string $route, callable|array $action): self
    {
        $this->routes[$route] = $action;

        return $this;
    }

    public function resolve(string $requestUri)
    {
        $requestUri = strtok($requestUri, "?");
        // $requestUri = explode("?", $requestUri)[0];

        $action = $this->routes[$requestUri] ?? null;

        if (!$action) {
            throw new RouteNotFoundException();
        }

        if (is_callable($action)) {
            return call_user_func($action);
        }

        if (is_array($action)) {
            dd("array yeaa");
        }

        throw new RouteNotFoundException();

        // dd($requestUri);
        // dd($action);
    }
}
