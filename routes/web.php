<?php

use Src\Controller\IndexController;
use Src\Router;
use Src\View;

$router = new Router();
$view = new View();
$index = new IndexController();

$router->get("/", function () use ($index) {
    $index->index();
});

$router->any("/404", function () use ($view) {
    $view->render("404", [
        "header" => APP_NAME . " | 404 Page not found",
    ]);
});
