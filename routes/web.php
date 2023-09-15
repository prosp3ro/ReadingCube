<?php

use Src\Controller\IndexController;
use Src\Controller\UserController;
use Src\Exception\AppException;
use Src\Router;
use Src\View;

$router = new Router();
$view = new View();
$IndexController = new IndexController();
$UserController = new UserController();

try {
    $router->get("/", function () use ($IndexController) {
        $IndexController->index();
    });

    $router->post("/login", function () use ($UserController) {
        $UserController->login();
    });

    $router->any("/404", function () use ($view) {
        $view->render("404", [
            "header" => APP_NAME . " | 404 Page not found",
        ]);
    });
} catch (Throwable $exception) {
    throw new AppException("Routing error.", 0, $exception);
}
