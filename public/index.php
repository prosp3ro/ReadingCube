<?php

declare(strict_types=1);

define('ROOT', __DIR__ . "/..");
define('APP_ENVIRONMENT', "development");

if (APP_ENVIRONMENT === "production") {
    require_once(ROOT . "/utils/production.php");
} elseif (APP_ENVIRONMENT === "development") {
    require_once(ROOT . "/utils/development.php");
}

require_once(ROOT . "/vendor/autoload.php");

try {
    require_once(ROOT . "/routes/web.php");
} catch (Throwable $exception) {
    dd($e);
    die();
}
