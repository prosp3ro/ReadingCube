<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\App;
use App\Helpers\Captcha;
use App\Models\User;
use App\View;
use ReflectionClass;

class LoginController
{
    private Captcha $captcha;

    public function __construct()
    {
        $this->captcha = App::resolve(Captcha::class);

        $reflect = new ReflectionClass($this->captcha);
        $con = $reflect->getConstructor();
        $par = $con->getParameters();

        dd($par);
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

        if (! $this->captcha->validateCaptcha($captchaResponseKey)) {
            exit("Captcha validation failed.");
        }

        if (empty($email) || empty($password)) {
            exit("Email and password are required.");
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            exit("Email has invalid format");
        }

        $user = (new User)->login($email);

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
