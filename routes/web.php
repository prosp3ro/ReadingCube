<?php

use Src\Controller\Auth\LoginController;
use Src\Controller\Auth\RegisterController;
use Src\Controller\IndexController;
use Src\Exception\AppException;
use Src\Route;
use Src\View;

$view = new View();
$IndexController = new IndexController();
$RegisterController = new RegisterController();
$LoginController = new LoginController();

try {
    Route::get("/", function () use ($IndexController) {
        $IndexController->index();
    });

    Route::get("/register", function () use ($RegisterController) {
        $RegisterController->index();
    });

    Route::get("/login", function () use ($LoginController) {
        $LoginController->index();
    });

    Route::post("/register", function () use ($RegisterController) {
        $RegisterController->register();
    });

    Route::any("/404", function () use ($view) {
        $view->render404();
    });
} catch (Throwable $exception) {
    throw new AppException("Routing error.", 0, $exception);
}
