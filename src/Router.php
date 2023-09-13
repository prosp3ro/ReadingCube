<?php

declare(strict_types=1);

namespace Src;

class Router
{
    public function get(string $route, callable $callback): void
    {
        if ($_SERVER['REQUEST_METHOD'] === "GET") {
            $this->route($route, $callback);
        }
    }

    public function post(string $route, callable $callback): void
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $this->route($route, $callback);
        }
    }

    public function put(string $route, callable $callback): void
    {
        if ($_SERVER['REQUEST_METHOD'] === "PUT") {
            $this->route($route, $callback);
        }
    }

    public function patch(string $route, callable $callback): void
    {
        if ($_SERVER['REQUEST_METHOD'] === "PATCH") {
            $this->route($route, $callback);
        }
    }

    public function delete(string $route, callable $callback): void
    {
        if ($_SERVER['REQUEST_METHOD'] === "DELETE") {
            $this->route($route, $callback);
        }
    }

    public function any(string $route, callable $callback): void
    {
        $this->route($route, $callback);
    }

    public function setCSRF(): void
    {
        session_start();

        if (!isset($_SESSION["csrf"])) {
            $_SESSION["csrf"] = bin2hex(random_bytes(50));
        }

        echo '<input type="hidden" name="csrf" value="' . $_SESSION["csrf"] . '">';
    }

    public function isCSRFValid(): bool
    {
        session_start();

        if (!isset($_SESSION['csrf']) || !isset($_POST['csrf'])) {
            return false;
        }

        if ($_SESSION['csrf'] != $_POST['csrf']) {
            return false;
        }

        return true;
    }

    private function sanitizeRequestUrl($url): string
    {
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = rtrim($url, '/');
        return (string) strtok($url, '?');
    }

    private function route(string $route, callable $callback): void
    {
        if ($route === "/404") {
            call_user_func($callback);
            exit();
        }

        $requestUrl = $this->sanitizeRequestUrl($_SERVER['REQUEST_URI']);

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
