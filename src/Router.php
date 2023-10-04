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
        // $this->routes[$route] = [
        //     "request_method" => $requestMethod,
        //     "action" => $action,
        // ];

        $this->routes[$requestMethod][$route] = $action;

        return $this;
    }

    public function get(string $route, callable|array $action)
    {
        return $this->register("get", $route, $action);
    }

    public function post(string $route, callable|array $action)
    {
        return $this->register("post", $route, $action);
    }

    public function put(string $route, callable|array $action)
    {
        return $this->register("put", $route, $action);
    }

    public function patch(string $route, callable|array $action)
    {
        return $this->register("patch", $route, $action);
    }

    public function delete(string $route, callable|array $action)
    {
        return $this->register("delete", $route, $action);
    }

    public function resolve(string $requestUri)
    {
        $requestUri = strtok($requestUri, "?");
        // $requestUri = explode("?", $requestUri)[0];

        $action = $this->routes["get"][$requestUri] ?? null;

        dd($action);
        die();

        if (!$action) {
            throw new RouteNotFoundException();
        }

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

            die();
        }

        throw new RouteNotFoundException();

        // dd($requestUri);
        // dd($action);
    }
}
