<?php

declare(strict_types=1);

namespace Src\Model;

use PDO;
use Src\Exception\ConfigurationException;
use Src\Exception\DatabaseConnectionException;
use Throwable;

class DB extends PDO
{
    public function __construct(string $file = __DIR__ . "/../../config/config.ini")
    {
        $file = str_replace('\\', '/', $file);

        if (!file_exists($file)) {
            throw new ConfigurationException("Configuration file <strong>{$file}</strong> does not exist.");
        }

        $connection = parse_ini_file($file, true);

        if (!$connection) {
            throw new ConfigurationException("Unable to parse <strong>{$file}</strong>.");
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
            throw new DatabaseConnectionException("Connection to the database failed.", 0, $exception);
        }
    }
}
