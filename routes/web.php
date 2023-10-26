<?php

use Src\Controllers\Auth\LoginController;
use Src\Controllers\Auth\RegisterController;
use Src\Controllers\IndexController;
use Src\Controllers\ItemController;
use Src\Controllers\UserController;
use Src\Helpers\Captcha;
use Src\Models\DB;
use Src\Route;

$db = new DB();
$captcha = new Captcha(GOOGLE_RECAPTCHA_SITE_KEY, GOOGLE_RECAPTCHA_SECRET_KEY);

$IndexController = new IndexController();
$RegisterController = new RegisterController();
$LoginController = new LoginController();
$UserController = new UserController();
$ItemController = new ItemController();

Route::get('/', function () use ($IndexController) {
    $IndexController->index();
});

Route::post('/upload', function () use ($IndexController) {
    $IndexController->upload();
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

Route::get('/register', function () use ($RegisterController, $captcha) {
    $RegisterController->index($captcha);
});

Route::post('/register', function () use ($RegisterController, $captcha) {
    $RegisterController->register($captcha);
});

Route::get('/login', function () use ($LoginController, $captcha) {
    $LoginController->index($captcha);
});

Route::post('/login', function () use ($LoginController, $captcha) {
    $LoginController->login($captcha);
});

Route::get('/logout', function () use ($LoginController) {
    $LoginController->logout();
});

Route::get('/edit-profile', function () use ($UserController, $captcha) {
    $UserController->showEditProfilePage($captcha);
});

Route::post('/edit-profile', function () use ($UserController, $captcha) {
    $UserController->updateProfile($captcha);
});

Route::post('/update-password', function () use ($UserController, $captcha) {
    $UserController->updatePassword($captcha);
});

Route::get('/item/$id', function (int $id) use ($ItemController) {
    $ItemController->index($id);
});

Route::any('/not-found', function () use ($IndexController) {
    $IndexController->pageNotFound();
});
