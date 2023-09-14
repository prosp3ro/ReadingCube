<?php

require_once(__DIR__ . "/../../utils/constants.php");
require_once(ROOT . "/vendor/autoload.php");

use Src\Model\DB;

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
