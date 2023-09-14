<?php

namespace Src\Exception;

use Exception;
use Throwable;

class DatabaseConnectionException extends Exception
{
    public function __construct($message = "Failed to connect to the database.", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
