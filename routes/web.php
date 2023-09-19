<?php

use Src\Controller\Auth\LoginController;
use Src\Controller\Auth\RegisterController;
use Src\Controller\IndexController;
use Src\Controller\UserController;
use Src\Route;
use Src\View;

$view = new View();
$IndexController = new IndexController();
$RegisterController = new RegisterController();
$LoginController = new LoginController();
$UserController = new UserController();

Route::get("/", function () use ($IndexController) {
    $IndexController->index();
});

Route::get("/register", function () use ($RegisterController) {
    $RegisterController->index();
});

Route::post("/register", function () use ($RegisterController) {
    $RegisterController->register();
});

Route::get("/register-success", function () use ($RegisterController) {
    $RegisterController->registerSuccess();
});

Route::get("/login", function () use ($LoginController) {
    $LoginController->index();
});

Route::post("/login", function () use ($LoginController) {
    $LoginController->login();
});

Route::get("/logout", function () use ($LoginController) {
    $LoginController->logout();
});

Route::get("/user-profile", function () use ($UserController) {
    $UserController->showProfile();
});

Route::any("/404", function () use ($view) {
    $view->render404();
});
