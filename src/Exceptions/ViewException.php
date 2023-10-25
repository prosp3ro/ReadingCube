<?php

declare(strict_types=1);

namespace Src\Exceptions;

class ViewException extends \Exception
{
    public static function viewNotFound(): static
    {
        return new static("View not found.");
    }
}
