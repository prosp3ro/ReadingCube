<?php

declare(strict_types=1);

namespace Src\Controller\Auth;

use Src\View;

class LoginController
{
    private View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function index()
    {
        return $this->view->render("auth/login", [
            "header" => "Login | ". APP_NAME
        ]);
    }
}
