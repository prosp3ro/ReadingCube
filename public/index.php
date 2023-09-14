<?php

declare(strict_types=1);

// TODO move to /.env
require_once(__DIR__ . "/../utils/constants.php");

if (APP_ENVIRONMENT === "production") {
    require_once(ROOT . "/utils/production.php");
} elseif (APP_ENVIRONMENT === "development") {
    require_once(ROOT . "/utils/development.php");
}

require_once(ROOT . "/vendor/autoload.php");

try {
    require_once(ROOT . "/routes/web.php");
} catch (Throwable $exception) {
    showException($exception);
}
