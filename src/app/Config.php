<?php

declare(strict_types=1);

namespace App;

class Config
{
    protected array $config = [];

    public function __construct(array $env)
    {
        $this->config = [
            "eloquent" => [
                "driver" => $env["DB_DRIVER"] ?? "mysql",
                "host" => $env["DB_HOST"],
                "database" => $env["DB_SCHEMA"],
                "username" => $env["DB_USERNAME"],
                "password" => $env["DB_PASSWORD"],
                "charset" => "utf8",
                "collation" => "utf8_unicode_ci",
                "prefix" => ""
            ],
            "doctrine" => [],
            "mailer" => [
                "dsn" => $env["MAILER_DSN"]
            ]
        ];
    }
}
