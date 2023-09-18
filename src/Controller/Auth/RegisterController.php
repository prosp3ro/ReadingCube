<?php

declare(strict_types=1);

namespace Src\Controller\Auth;

use Src\View;

class RegisterController
{
    private View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function index()
    {
        return $this->view->render("auth/register", [
            "header" => "Register | " . APP_NAME
        ]);
    }

    public function register()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (!isset($email) || !isset($password)) {
            return $this->view->render404();
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

        } else {
            echo "Please enter a valid email address.";
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    }
}
