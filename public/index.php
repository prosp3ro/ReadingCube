<?php

declare(strict_types=1);

define('ROOT', __DIR__ . "/..");
define('APP_ENVIRONMENT', "development");

use Src\View;
use Src\Controller\IndexController;
use Src\Router;

if (APP_ENVIRONMENT === "production") {
    require_once(ROOT . "/utils/production.php");
} elseif (APP_ENVIRONMENT === "development") {
    require_once(ROOT . "/utils/development.php");
}

require_once(ROOT . "/vendor/autoload.php");

// $db = new DB();

// $sql = "SELECT * FROM users";
// $statement = $db->prepare($sql);
// $statement->execute();
// $results = $statement->fetchAll();

// dd($results);
// dd($_SERVER);

$view = new View();
$view->render("index");

$user = new IndexController();
$router = new Router();

$router->get("/", function () use ($user) {
    $user->index();
});

try {
} catch (Throwable $exception) {
    dd($exception);
}
