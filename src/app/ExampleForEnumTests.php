<?php

declare(strict_types=1);

namespace App;

use App\Enums\ExampleEnum;

class ExampleForEnumTests
{
    public function asd(ExampleEnum $enum)
    {
        dump($enum);
        dump($enum->text());
        dump($enum->value);
        dump($enum->name);
        die();
    }
}
