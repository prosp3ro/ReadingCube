<?php

declare(strict_types=1);

namespace App\Models;

use PDO;
use PDOException;

/**
 * @mixin PDO
 */
class DB
{
    private PDO $pdo;

    public function __construct(private $config = [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false
    ])
    {
        $dbDriver = $_ENV["DB_DRIVER"] ?? "mysql";
        $dbHost = $_ENV["DB_HOST"];
        $dbPort = (!empty($_ENV["DB_PORT"])) ? (";port={$_ENV['DB_PORT']}") : "";
        $dbName = $_ENV["DB_SCHEMA"];

        $dsn = "{$dbDriver}:host={$dbHost}{$dbPort};dbname={$dbName}";

        $dbUsername = $_ENV["DB_USERNAME"];
        $dbPassword = $_ENV["DB_PASSWORD"];

        try {
            $this->pdo = new PDO($dsn, $dbUsername, $dbPassword, $config);
        } catch (PDOException $exception) {
            throw new PDOException($exception->getMessage(), $exception->getCode());
        }
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([
            $this->pdo, $name
        ], $arguments);
    }
}
