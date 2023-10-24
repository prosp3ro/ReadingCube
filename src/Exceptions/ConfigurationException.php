<?php

namespace Src\Exceptions;

use Exception;
use Throwable;

class ConfigurationException extends Exception
{
    protected $message = "Problem with configuration occured.";

    // public function __construct(string $message = "Problem with configuration occured.", int $code = 0, Throwable $previous = null)
    // {
    //     parent::__construct($message, $code, $previous);
    // }
}
