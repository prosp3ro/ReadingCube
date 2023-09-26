<?php

declare(strict_types=1);

namespace Src;

class Route
{
    public static function get(string $route, callable $callback): void
    {
        if ($_SERVER['REQUEST_METHOD'] === "GET") {
            self::route($route, $callback);
        }
    }

    public static function post(string $route, callable $callback): void
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            self::route($route, $callback);
        }
    }

    public static function put(string $route, callable $callback): void
    {
        if ($_SERVER['REQUEST_METHOD'] === "PUT") {
            self::route($route, $callback);
        }
    }

    public static function patch(string $route, callable $callback): void
    {
        if ($_SERVER['REQUEST_METHOD'] === "PATCH") {
            self::route($route, $callback);
        }
    }

    public static function delete(string $route, callable $callback): void
    {
        if ($_SERVER['REQUEST_METHOD'] === "DELETE") {
            self::route($route, $callback);
        }
    }

    public static function any(string $route, callable $callback): void
    {
        self::route($route, $callback);
    }

    private static function sanitizeRequestUrl($url): string
    {
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = rtrim($url, '/');
        return (string) strtok($url, '?');
    }

    private static function route(string $route, callable $callback): void
    {
        if ($route === "/not-found") {
            call_user_func($callback);
            exit();
        }

        $requestUrl = self::sanitizeRequestUrl($_SERVER['REQUEST_URI']);

        $routeParts = explode('/', $route);
        array_shift($routeParts);

        if (str_contains($requestUrl, "/")) {
            $requestUrlParts = explode('/', $requestUrl);
        } else {
            $requestUrlParts = explode(' ', $requestUrl);
        }

        array_shift($requestUrlParts);

        if ($routeParts[0] === "" && count($requestUrlParts) === 0) {
            call_user_func($callback);
            exit();
        }

        if (count($routeParts) != count($requestUrlParts)) {
            return;
        }

        $parameters = [];

        foreach ($routeParts as $index => $routePart) {
            if (preg_match("/^[$]/", $routePart)) {
                $parameterName = ltrim($routePart, '$');
                $parameters[] = $requestUrlParts[$index];
                ${$parameterName} = $requestUrlParts[$index];
            } elseif ($routePart != $requestUrlParts[$index]) {
                return;
            }
        }
        
        call_user_func_array($callback, $parameters);
        exit();
    }
}
