<?php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Src\View;

define('ROOT', __DIR__ . "/..");

$configFile = ROOT . "/config/config.ini";
$configFile = str_replace('\\', '/', $configFile);

if (!file_exists($configFile)) {
    throw new Exception("Configuration file <strong>{$configFile}</strong> does not exist.");
}

$config = parse_ini_file($configFile, true);

if (!$config) {
    throw new Exception("Unable to parse <strong>{$configFile}</strong>.");
}

define('APP_NAME', $config['app']['name'] ?? "App");
define('GOOGLE_RECAPTCHA_SITE_KEY', $config['app']['google_recaptcha_site_key'] ?? "");
define('GOOGLE_RECAPTCHA_SECRET_KEY', $config['app']['google_recaptcha_secret_key'] ?? "");
define('EMAILABLE_API_KEY', $config['app']['emailable_api_key'] ?? "");

define('PARTIALS', ROOT . "/templates/partials");

if ($config['app']['debug']) {
    require_once(ROOT . "/utils/debug.php");
}

if ($config['app']['env'] == "production") {
    require_once(ROOT . "/utils/production.php");
}

require_once(ROOT . "/utils/urlIs.php");
require_once(ROOT . "/vendor/autoload.php");

ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

session_set_cookie_params([
    "lifetime" => 86400 * 7,
    "domain" => $config["app"]["domain"] ?? "localhost",
    "path" => "/",
    "secure" => true,
    "httponly" => true
]);

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

$database = $config["database"];

$capsule->addConnection([
    "driver" => $database["driver"],
    "host" => $database["host"],
    "database" => $database["schema"],
    "username" => $database["username"],
    "password" => $database["password"]
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

try {
    require_once(ROOT . "/routes/web.php");
} catch (Throwable $exception) {
    $exceptionClassName = get_class($exception);
    $errorLogMessage = date('Y-m-d H:i:s') . PHP_EOL .
        "Exception: {$exceptionClassName}" . PHP_EOL .
        "Message: {$exception->getMessage()}" . PHP_EOL .
        "File: {$exception->getFile()}" . PHP_EOL .
        "Line: {$exception->getLine()}" . PHP_EOL . PHP_EOL;

    error_log($errorLogMessage, 3, ROOT . "/logs/error.log");

    if (function_exists("showException")) {
        showException($exception);
    } else {
        $view = new View();
        $view->render("error-page");
    }
}
