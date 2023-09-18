<?php

use Src\Controller\Auth\LoginController;
use Src\Controller\Auth\RegisterController;
use Src\Controller\IndexController;
use Src\Route;
use Src\View;

$view = new View();
$IndexController = new IndexController();
$RegisterController = new RegisterController();
$LoginController = new LoginController();

Route::get("/", function () use ($IndexController) {
    $IndexController->index();
});

Route::get("/register", function () use ($RegisterController) {
    $RegisterController->index();
});

Route::get("/register-success", function () use ($RegisterController) {
    $RegisterController->registerSuccess();
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
