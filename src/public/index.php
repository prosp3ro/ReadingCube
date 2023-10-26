<?php

declare(strict_types=1);

use App\Controllers\Auth\LoginController;
use App\Controllers\Auth\RegisterController;
use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Controllers\IndexController;
use App\Controllers\ItemController;
use App\Controllers\UserController;
use App\Exceptions\RouteException;
use App\Models\DB;
use App\Router;
use App\View;

define('ROOT', dirname(__DIR__));
define('PARTIALS', ROOT . "/templates/partials");
define('STORAGE_PATH', ROOT . "/storage");
define('VIEW_PATH', ROOT . "/templates/views");

date_default_timezone_set('Europe/Warsaw');
ini_set("max_execution_time", 15);

require_once ROOT . "/vendor/autoload.php";
require_once ROOT . "/utils/urlIs.php";

$repository = \Dotenv\Repository\RepositoryBuilder::createWithNoAdapters()
    ->addAdapter(\Dotenv\Repository\Adapter\EnvConstAdapter::class)
    ->addWriter(\Dotenv\Repository\Adapter\PutenvAdapter::class)
    ->immutable()
    ->make();

$dotenv = Dotenv::create($repository, ROOT);
// $dotenv->required('APP_DEBUG')->isBoolean();
$dotenv->load();

define('APP_NAME', $_ENV["APP_NAME"] ?? "App");
define('GOOGLE_RECAPTCHA_SITE_KEY', $_ENV["GOOGLE_RECAPTCHA_SITE_KEY"] ?? "");
define('GOOGLE_RECAPTCHA_SECRET_KEY', $_ENV["GOOGLE_RECAPTCHA_SECRET_KEY"] ?? "");
define('EMAILABLE_API_KEY', $_ENV["EMAILABLE_API_KEY"] ?? "");

// TODO make it a boolean, not string
if ($_ENV["APP_DEBUG"] === "true") {
    include_once ROOT . "/utils/debug.php";
}

if ($_ENV["APP_ENVIRONMENT"] === "production") {
    include_once ROOT . "/utils/production.php";
}

set_exception_handler(
    function (Throwable $exception) {
        $exceptionClassName = get_class($exception);
        $errorLogMessage = date('Y-m-d H:i:s') . PHP_EOL .
            "Exception: {$exceptionClassName}" . PHP_EOL .
            "Message: {$exception->getMessage()}" . PHP_EOL .
            "File: {$exception->getFile()}" . PHP_EOL .
            "Line: {$exception->getLine()}" . PHP_EOL . PHP_EOL;

        error_log($errorLogMessage, 3, ROOT . "/logs/error.log");

        if (is_callable("showException")) {
            showException($exception);
        }
    }
);

ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

session_set_cookie_params(
    [
        "lifetime" => 86400 * 7,
        "domain" => $_ENV["APP_DOMAIN"] ?? "localhost",
        "path" => "/",
        "secure" => true,
        "httponly" => true
    ]
);

session_start();

if (!isset($_SESSION["last_regeneration"])) {
    session_regenerate_id(true);
    $_SESSION["last_regeneration"] = time();
} else {
    $interval = 60 * 30;

    if (time() - $_SESSION["last_regeneration"] >= $interval) {
        session_regenerate_id(true);
        $_SESSION["last_regeneration"] = time();
    }
}

$capsule = new Capsule();

$capsule->addConnection(
    [
        "driver" => $_ENV["DB_DRIVER"] ?? "mysql",
        "host" => $_ENV["DB_HOST"],
        "database" => $_ENV["DB_SCHEMA"],
        "username" => $_ENV["DB_USERNAME"],
        "password" => $_ENV["DB_PASSWORD"],
        "charset" => "utf8",
        "collation" => "utf8_unicode_ci",
        "prefix" => ""
    ]
);

$capsule->setAsGlobal();
$capsule->bootEloquent();

// dd($_ENV);

// $db = new DB();
// $user = $db->query("SELECT * FROM users")->fetchAll();

// dd($user);

// die();

try {
    $router = new Router();

    // TODO inject captcha object
    $router
        ->get("/", [IndexController::class, "index"])
        ->get("/about-us", [IndexController::class, "showAboutUsPage"])
        ->get("/contact", [IndexController::class, "showContactPage"])
        ->get("/faq", [IndexController::class, "showFAQPage"])
        ->post("/upload", [IndexController::class, "upload"])

        ->get("/register", [RegisterController::class, "index"])
        ->post("/register", [RegisterController::class, "register"])

        ->get("/login", [LoginController::class, "index"])
        ->post("/login", [LoginController::class, "login"])
        ->get("/logout", [LoginController::class, "logout"])

        // TODO method should be `index`
        ->get("/edit-profile", [UserController::class, "showEditProfilePage"])
        // updateProfileData
        ->post("/edit-profile", [UserController::class, "updateProfile"])
        ->post("/update-password", [UserController::class, "updatePassword"]);

        // TODO
        // ->get("/item/{id}", [ItemController::class, "index"])

    $router->resolve(
        $_SERVER["REQUEST_URI"],
        $_SERVER["REQUEST_METHOD"]
    );
} catch (RouteException) {
    // header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    http_response_code(404);

    View::create('error/404', [
        "header" => "Not Found | " . APP_NAME
    ])->render();
}

// setcookie(
//     "username",
//     "prospero",
//     time() + 10, // expiration time
// );

// dd($_COOKIE);

// require_once ROOT . "/routes/web.php";
