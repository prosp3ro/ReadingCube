<?php

declare(strict_types = 1);

namespace App;

class View
{
    public static function getPath(string $str): string
    {
        return str_replace('\\', '/', $str);
    }

    public function render(): void
    {
        require_once(ROOT . "/templates/views/index.php");
    }
}
