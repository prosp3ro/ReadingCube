<?php

declare(strict_types = 1);

function urlIs(string $url)
{
    if ($_SERVER["REQUEST_URI"] == $url) {
        return "text-secondary";
    } else {
        return "text-white";
    }
}
