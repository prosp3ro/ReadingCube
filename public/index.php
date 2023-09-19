<?php

declare(strict_types=1);

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

try {
    session_start();
    require_once(ROOT . "/routes/web.php");
} catch (Throwable $exception) {
    if (function_exists("showException")) {
        showException($exception);
    } else {
        echo "Error occured. Please try again later.";
    }
}
