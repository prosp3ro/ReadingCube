<?php

declare(strict_types = 1);

use Src\Model\DB;

define('ROOT', __DIR__ . "/..");

function isProductionEnvironment()
{
    return false;
}

require_once(ROOT . "/utils/development.php");
// require_once(ROOT . "/utils/production.php");
require_once(ROOT . "/vendor/autoload.php");

$db = new DB();

$sql = "SELECT * FROM users";
$statement = $db->prepare($sql);
$statement->execute();
$results = $statement->fetchAll();

dd($results);
