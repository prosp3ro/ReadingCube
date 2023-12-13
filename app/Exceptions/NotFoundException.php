<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class NotFoundException extends Exception
{
    protected $message = "Resource not found.";

    // public function __construct($message = "Resource not found.", $code = 0, Throwable $previous = null)
    // {
    //     parent::__construct($message, $code, $previous);
    // }
}
