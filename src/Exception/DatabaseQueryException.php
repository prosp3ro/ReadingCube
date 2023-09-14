<?php

namespace Src\Exception;

use Exception;
use Throwable;

class DatabaseQueryException extends Exception
{
    public function __construct($message = "Database query error.", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
