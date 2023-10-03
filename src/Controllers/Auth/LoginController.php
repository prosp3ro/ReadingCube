<?php

declare(strict_types=1);

namespace Src\Controllers\Auth;

use Illuminate\Database\Capsule\Manager as DB;
use Src\View;

class LoginController
{
    private View $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    public function index(object $captcha)
    {
        if (isset($_SESSION['user_id'])) {
            header("Location: /");
            exit();
        }

        $registerMessage = $_GET["register"] ?? "";

        return $this->view->render("auth/login", [
            "header" => "Login | " . APP_NAME,
            "captcha" => $captcha,
            "registerMessage" => $registerMessage
        ]);
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
        header("Location: /login");
        exit();
    }

    public function login(object $captcha)
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $captchaResponseKey = $_POST['g-recaptcha-response'];

        if (!$captcha->validateCaptcha($captchaResponseKey)) {
            exit("Captcha validation failed.");
        }

        if (empty($email) || empty($password)) {
            exit("Email and password are required.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            exit("Email has invalid format");
        }

        $user = DB::table("users")
            ->select("*")
            ->where("email", "=", $email)
            ->first()
        ;

        if ($user) {
            if (password_verify($password, $user->password)) {
                session_start();
                session_regenerate_id();
                $_SESSION["user_id"] = $user->id;

                header("Location: /");
                exit();
            }
        }

        exit("Email or password is incorrect.");
    }
}
