<?php

declare(strict_types = 1);

namespace Src;

class Router
{
    private string $viewsPath;

    public function __construct()
    {
        $this->viewsPath = ROOT . "/templates/views";
    }

    public function get(string $route, string|callable $pathToInclude): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->route($route, $pathToInclude);
        }
    }

    public function post(string $route, string|callable $pathToInclude): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->route($route, $pathToInclude);
        }
    }

    public function put(string $route, string|callable $pathToInclude): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            $this->route($route, $pathToInclude);
        }
    }

    public function patch(string $route, string|callable $pathToInclude): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
            $this->route($route, $pathToInclude);
        }
    }

    public function delete(string $route, string|callable $pathToInclude): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            $this->route($route, $pathToInclude);
        }
    }

    public function any(string $route, string|callable $pathToInclude): void
    {
        $this->route($route, $pathToInclude);
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

    private function route(string $route, string|callable $pathToInclude): void
    {
        $callback = $pathToInclude;

        if (!is_callable($callback)) {
            if (!strpos($pathToInclude, '.view.php')) {
                $pathToInclude .= '.view.php';
            }
        }

        // dd($route);
        // die();
        if ($route == "/404") {
            include_once $this->viewsPath . "/$pathToInclude";
            exit();
        }

        $requestUrl = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
        $requestUrl = rtrim($requestUrl, '/');
        $requestUrl = (string) strtok($requestUrl, '?');

        $routeParts = explode('/', $route);
        array_shift($routeParts);

        if (str_contains($requestUrl, "/")) {
            $requestUrlParts = explode('/', $requestUrl);
        } else {
            $requestUrlParts = explode(' ', $requestUrl);
        }

        array_shift($requestUrlParts);

        if ($routeParts[0] == "" && count($requestUrlParts) == 0) {
            if (is_callable($callback)) {
                call_user_func($callback);
                exit();
            }

            include_once $this->viewsPath . "/$pathToInclude";
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

        if (is_callable($callback)) {
            call_user_func_array($callback, $parameters);
            exit();
        }

        include_once $this->viewsPath . "/$pathToInclude";
        exit();
    }
}
