<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\View;

class ResetPasswordController
{
    public function index()
    {
        $token = bin2hex(random_bytes(16));
        $tokenHash = hash("sha256", $token);
        // dd($tokenHash);

        $tokenExpirationTime = time() + 60 * 5;
        $tokenExpirationTime = date("Y-m-d H:i:s", $tokenExpirationTime);
        // dd($tokenExpirationTime);

        View::create("auth/forgot-password")->render();
    }

    public function resetPassword()
    {
        dd($_POST);
    }
}
