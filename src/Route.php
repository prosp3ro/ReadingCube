<?php

declare(strict_types=1);

namespace Src;

class Route
{
    public static function get(string $route, callable $userFunction): void
    {
        if ($_SERVER['REQUEST_METHOD'] === "GET") {
            self::route($route, $userFunction);
        }
    }

    public static function post(string $route, callable $userFunction): void
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            self::route($route, $userFunction);
        }
    }

    public static function put(string $route, callable $userFunction): void
    {
        if ($_SERVER['REQUEST_METHOD'] === "PUT") {
            self::route($route, $userFunction);
        }
    }

    public static function patch(string $route, callable $userFunction): void
    {
        if ($_SERVER['REQUEST_METHOD'] === "PATCH") {
            self::route($route, $userFunction);
        }
    }

    public static function delete(string $route, callable $userFunction): void
    {
        if ($_SERVER['REQUEST_METHOD'] === "DELETE") {
            self::route($route, $userFunction);
        }
    }

    public static function any(string $route, callable $userFunction): void
    {
        self::route($route, $userFunction);
    }

    private static function sanitizeRequestUrl($url): string
    {
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = rtrim($url, '/');
        return (string) strtok($url, '?');
    }

    private static function route(string $route, callable $userFunction): void
    {
        if ($route === "/not-found") {
            call_user_func($userFunction);
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
            call_user_func($userFunction);
            exit();
        }

        if (count($routeParts) != count($requestUrlParts)) {
            return;
        }

        $parameters = [];

        foreach ($routeParts as $routeIndex => $routePart) {
            if (preg_match("/^[$]/", $routePart)) {
                $parameters[] = $requestUrlParts[$routeIndex];
            } elseif ($routePart != $requestUrlParts[$routeIndex]) {
                return;
            }
        }

        // if (!empty($parameters)) {
        //     // extract($parameters);

        //     // dd($parameters);
        //     // die();

        //     // it doesnt support named parameters
        //     call_user_func_array($userFunction, $parameters);
        //     exit();
        // }

        // dd($parameters);
        // die();

        call_user_func_array($userFunction, $parameters);
        exit();
    }
}
