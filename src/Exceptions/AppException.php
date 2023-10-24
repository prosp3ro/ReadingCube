<?php

namespace Src\Exceptions;

use Exception;
use Throwable;

class AppException extends Exception
{
    protected $message = "An error occurred in the application.";

    // public function __construct($message = "An error occurred in the application.", $code = 0, Throwable $previous = null)
    // {
    //     parent::__construct($message, $code, $previous);
    // }
}
