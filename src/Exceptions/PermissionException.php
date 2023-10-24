<?php

namespace Src\Exceptions;

use Exception;
use Throwable;

class PermissionException extends Exception
{
    protected $message = "Permission denied.";

    // public function __construct($message = "Permission denied.", $code = 0, Throwable $previous = null)
    // {
    //     parent::__construct($message, $code, $previous);
    // }
}
