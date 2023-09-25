<?php

declare(strict_types=1);

define('ROOT', __DIR__ . "/..");

require_once(ROOT . "/vendor/autoload.php");

use Src\Models\DB;

$db = new DB();
$seeder = new \tebazil\dbseeder\Seeder($db);
$generator = $seeder->getGeneratorConfigurator();
$faker = $generator->getFakerConfigurator();

$seeder->table('books')->columns([
    'id',
    'book_name' => $faker->company,
    'book_author' => $faker->name,
    'book_year' => $faker->year
])->rowQuantity(100);

$seeder->refill();
