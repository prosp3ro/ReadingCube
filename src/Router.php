<?php

declare(strict_types=1);

namespace Src;

use Src\Exceptions\RouteNotFoundException;

class Router
{
    private array $routes;

    public function __construct()
    {
    }

    public function register(string $requestMethod, string $route, callable|array $action): self
    {
        $this->routes[$requestMethod][$route] = $action;

        return $this;
    }

    public function get(string $route, callable|array $action): self
    {
        return $this->register("get", $route, $action);
    }

    public function post(string $route, callable|array $action): self
    {
        return $this->register("post", $route, $action);
    }

    public function put(string $route, callable|array $action): self
    {
        return $this->register("put", $route, $action);
    }

    public function patch(string $route, callable|array $action): self
    {
        return $this->register("patch", $route, $action);
    }

    public function delete(string $route, callable|array $action): self
    {
        return $this->register("delete", $route, $action);
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function resolve(string $requestUri, string $requestMethod)
    {
        $requestUri = strtok($requestUri, "?");
        // $requestUri = explode("?", $requestUri)[0];
        $requestMethod = strtolower($requestMethod);

        $route = $this->routes[$requestMethod] ?? null;
        $action = $route[urldecode($requestUri)] ?? null;

        if (!$route || !$action) {
            throw new RouteNotFoundException();
        }

        dd($action);
        die();

        // if (preg_match("/^\/[{].*[}]/", key($route))) {
        //     dd("y");
        // }

        // die();

        if (is_callable($action)) {
            return call_user_func($action);
        }

        if (is_array($action)) {
            [$class, $method] = $action;

            if (class_exists($class)) {
                $class = new $class();

                if (method_exists($class, $method)) {
                    // $class->$method();
                    return call_user_func_array([$class, $method], []);
                }
            }
        }

        throw new RouteNotFoundException();
    }
}
