<?php

namespace Src\Exception;

use Exception;
use Throwable;

class ConfigurationException extends Exception
{
    public function __construct($message = "Problem with configuration occured.", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
