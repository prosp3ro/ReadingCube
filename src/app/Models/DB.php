<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class DB extends PDO
{
    public function __construct()
    {
        // $file = str_replace('\\', '/', $file);

        // if (! file_exists($file)) {
        //     throw new ConfigurationException("Configuration file <strong>{$file}</strong> does not exist.");
        // }

        // $connection = parse_ini_file($file, true);

        // if (! $connection) {
        //     throw new ConfigurationException("Unable to parse <strong>{$file}</strong>.");
        // }

        // $database = $connection['database'];

        $dbDriver = $_ENV["DB_DRIVER"];
        $dbHost = $_ENV["DB_HOST"];
        $dbPort = (! empty($_ENV["DB_PORT"])) ? (";port={$_ENV['DB_PORT']}") : "";
        $dbName = $_ENV["DB_SCHEMA"];

        $dsn = "{$dbDriver}:host={$dbHost}{$dbPort};dbname={$dbName}";

        $dbUsername = $_ENV["DB_USERNAME"];
        $dbPassword = $_ENV["DB_PASSWORD"];

        parent::__construct($dsn, $dbUsername, $dbPassword, [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }
}
