<?php

declare(strict_types=1);

namespace Src\Model;

use PDO;
use Src\Exception\ConfigurationException;
use Throwable;

class DB extends PDO
{
    public function __construct(string $file = ROOT . "/config/config.ini")
    {
        $file = str_replace('\\', '/', $file);
        $connection = parse_ini_file($file, true);

        if (!$connection) {
            throw new ConfigurationException("Unable to open {$file}.");
        }

        $database = $connection['database'];

        $dbDriver = $database['driver'];
        $dbHost = $database['host'];
        $dbPort = ((!empty($database['port'])) ? (";port={$database['port']}") : "");
        $dbName = $database['schema'];

        $dsn = "{$dbDriver}:host={$dbHost}{$dbPort};dbname={$dbName}";

        $dbUsername = $database['username'];
        $dbPassword = $database['password'];

        try {
            parent::__construct($dsn, $dbUsername, $dbPassword, [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        } catch (Throwable $exception) {
            throw new ConfigurationException("Config error");

            if (APP_ENVIRONMENT === "production") {
                echo "<h1>An error occurred. Please try again later.</h1>";
            } else if (APP_ENVIRONMENT === "development") {
                showException($exception);
            }
        }
    }
}
