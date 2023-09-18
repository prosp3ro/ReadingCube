<?php

namespace Src\Exception;

use Exception;
use Throwable;

class DatabaseQueryException extends Exception
{
    public function __construct($message = "An error occurred while executing the database query.", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
