<?php

declare(strict_types=1);

if (!function_exists('urlIs')) {
    function urlIs(string $url): string
    {
        if ($_SERVER["REQUEST_URI"] == $url) {
            return "text-secondary";
        } else {
            return "text-white";
        }
    }
}
