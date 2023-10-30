<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Helpers\Captcha;
use Illuminate\Database\Capsule\Manager as DB;
use App\View;

class LoginController
{
    public function __construct(private $captcha = new Captcha(GOOGLE_RECAPTCHA_SITE_KEY, GOOGLE_RECAPTCHA_SECRET_KEY))
    {
    }

    public function index()
    {
        if (isset($_SESSION['user_id'])) {
            header("Location: /");
            exit();
        }

        $registerMessage = $_GET["register"] ?? "";

        return View::create("auth/login", [
            "header" => "Login | " . APP_NAME,
            "captcha" => $this->captcha,
            "registerMessage" => $registerMessage
        ])->render();
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
        header("Location: /login");
        exit();
    }

    public function login()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $captchaResponseKey = $_POST['g-recaptcha-response'];

        if (!$this->captcha->validateCaptcha($captchaResponseKey)) {
            exit("Captcha validation failed.");
        }

        if (empty($email) || empty($password)) {
            exit("Email and password are required.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            exit("Email has invalid format");
        }

        $user = DB::table("users")
            ->select("id", "email", "password")
            ->where("email", "=", $email)
            ->first();

        if ($user) {
            if (password_verify($password, $user->password)) {
                session_regenerate_id();
                $_SESSION["user_id"] = $user->id;

                // dd($user);

                // dd($_SESSION);

                header("Location: /");
                exit();
            }
        }

        exit("Email or password is incorrect.");
    }
}
