<?php

use Src\Controllers\Auth\LoginController;
use Src\Controllers\Auth\RegisterController;
use Src\Controllers\IndexController;
use Src\Controllers\UserController;
use Src\Helpers\Captcha;
use Src\Models\DB;
use Src\Route;
use Src\View;

$db = new DB();
$view = new View();
$captcha = new Captcha(GOOGLE_RECAPTCHA_SITE_KEY, GOOGLE_RECAPTCHA_SECRET_KEY);

$IndexController = new IndexController($view, $db);
$RegisterController = new RegisterController($view, $db);
$LoginController = new LoginController($view, $db);
$UserController = new UserController();

Route::get('/', function () use ($IndexController) {
    $IndexController->index();
});

Route::get('/about-us', function () use ($IndexController) {
    $IndexController->showAboutUsPage();
});

Route::get('/contact', function () use ($IndexController) {
    $IndexController->showContactPage();
});

Route::get('/faq', function () use ($IndexController) {
    $IndexController->showFAQPage();
});

Route::get('/register', function () use ($RegisterController) {
    $RegisterController->index();
});

Route::post('/register', function () use ($RegisterController) {
    $RegisterController->register();
});

Route::get('/login', function () use ($LoginController) {
    $LoginController->index();
});

Route::post('/login', function () use ($LoginController, $captcha) {
    $LoginController->login($captcha);
});

Route::get('/logout', function () use ($LoginController) {
    $LoginController->logout();
});

Route::get('/user-profile', function () use ($UserController) {
    $UserController->showProfile();
});

Route::any('/not-found', function () use ($view) {
    $view->pageNotFound();
});
