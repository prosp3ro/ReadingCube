<?php

namespace Src;

class Router
{
    private string $viewsPath = ROOT . "/templates/views";

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

    public function out($text): void
    {
        echo htmlspecialchars($text);
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

    private function route($route, $pathToInclude): void
    {
        $callback = $pathToInclude;

        if (!is_callable($callback)) {
            if (!strpos($pathToInclude, '.view.php')) {
                $pathToInclude .= '.view.php';
            }
        }

        if ($route == "/404") {
            include_once ROOT . "/$pathToInclude";
            exit();
        }

        $request_url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
        $request_url = rtrim($request_url, '/');
        $request_url = strtok($request_url, '?');

        $route_parts = explode('/', $route);
        array_shift($route_parts);

        $request_url_parts = explode('/', $request_url);
        array_shift($request_url_parts);

        if ($route_parts[0] == '' && count($request_url_parts) == 0) {
            if (is_callable($callback)) {
                call_user_func_array($callback, []);
                exit();
            }

            include_once $this->viewsPath . "/$pathToInclude";
            exit();
        }

        if (count($route_parts) != count($request_url_parts)) {
            return;
        }

        $parameters = [];

        for ($__i__ = 0; $__i__ < count($route_parts); $__i__++) {
            $route_part = $route_parts[$__i__];

            if (preg_match("/^[$]/", $route_part)) {
                $route_part = ltrim($route_part, '$');
                array_push($parameters, $request_url_parts[$__i__]);
                $$route_part = $request_url_parts[$__i__];
            } else if ($route_parts[$__i__] != $request_url_parts[$__i__]) {
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
