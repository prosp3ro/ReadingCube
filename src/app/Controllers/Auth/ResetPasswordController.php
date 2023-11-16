<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\View;

class ResetPasswordController
{
    public function index()
    {
        View::create("auth/forgot-password")->render();
    }

    public function resetPassword()
    {
        dd($_POST);
    }
}
