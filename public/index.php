<?php

declare(strict_types=1);

use Src\View;

define('ROOT', __DIR__ . "/..");

$config = parse_ini_file(ROOT . "/config/config.ini", true);
define('APP_NAME', $config['app']['name'] ?? "App");

define('PARTIALS', ROOT . "/templates/partials");

if ($config['app']['debug']) {
    require_once(ROOT . "/utils/debug.php");
}

if ($config['app']['env'] == "production") {
    require_once(ROOT . "/utils/production.php");
}

require_once(ROOT . "/utils/functions.php");
require_once(ROOT . "/vendor/autoload.php");

ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

session_set_cookie_params([
    "lifetime" => 1800, // 30 minutes
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
    $interval = 60 * 30; // 30 minutes

    if (time() - $_SESSION["last_regeneration"] >= $interval) {
        session_regenerate_id(true);
        $_SESSION["last_regeneration"] = time();
    }
}

try {
    require_once(ROOT . "/routes/web.php");
} catch (Throwable $exception) {
    if (function_exists("showException")) {
        showException($exception);
    } else {
        $view = new View();
        $view->render("error-page");
    }
}
