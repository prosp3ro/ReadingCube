<?php

namespace Src\Exception;

use Exception;
use Throwable;

class AppException extends Exception
{
    public function __construct($message = "An error occurred in the application.", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
