<?php

declare(strict_types=1);

namespace Src\Exceptions;

class RouteNotFoundException extends \Exception
{
    protected $message = "404 Route Not Found";
}
