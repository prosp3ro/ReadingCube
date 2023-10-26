<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class DatabaseQueryException extends Exception
{
    protected $message = "An error occurred while executing the database query.";

    // public function __construct($message = "An error occurred while executing the database query.", $code = 0, Throwable $previous = null)
    // {
    //     parent::__construct($message, $code, $previous);
    // }
}
