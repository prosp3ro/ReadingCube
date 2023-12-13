<?php

declare(strict_types=1);

namespace App\Helpers;

class CsrfTokenManager
{
    public static function generateToken(): string
    {
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        return $token;
    }

    public static function verifyToken($token): bool
    {
        if (isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === $token) {
            unset($_SESSION['csrf_token']);
            return true;
        }

        return false;
    }
}
