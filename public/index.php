<?php

use Src\Controller\UserController;

const ROOT = __DIR__ . "/..";

function isProductionEnvironment()
{
    return false;
}

require(ROOT . "/vendor/autoload.php");

$user = new UserController();
$user->sayHi();
