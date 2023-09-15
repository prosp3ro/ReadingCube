<?php

use Src\Controller\IndexController;
use Src\Controller\UserController;
use Src\Exception\AppException;
use Src\Route;
use Src\View;

$view = new View();
$IndexController = new IndexController();
$UserController = new UserController();

try {
    Route::get("/", function () use ($IndexController) {
        $IndexController->index();
    });

    Route::post("/login", function () use ($UserController) {
        $UserController->login();
    });

    Route::any("/404", function () use ($view) {
        $view->render("404", [
            "header" => APP_NAME . " | 404 Page not found",
        ]);
    });
} catch (Throwable $exception) {
    throw new AppException("Routing error.", 0, $exception);
}
