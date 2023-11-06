<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\App;
use App\Helpers\Captcha;
use App\Helpers\CsrfTokenManager;
use App\Models\User;
use App\Validator;
use App\View;

class RegisterController
{
    private $captcha;

    public function __construct()
    {
        $this->captcha = App::resolve(Captcha::class);
    }

    public function index()
    {
        if (isset($_SESSION['user_id'])) {
            header("Location: /");
            exit();
        }

        $csrfToken = CsrfTokenManager::generateToken();

        // TODO it should be done differently
        $email = $_GET["email"] ?? "";
        $validator = new Validator();
        $validator->isEmailAvailableJson($email);

        return View::create("auth/register", [
            "header" => "Register | " . APP_NAME,
            "captcha" => $this->captcha,
            "csrfToken" => $csrfToken
        ])->render();
    }

    public function register(): void
    {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $passwordConfirmation = $_POST['password_confirmation'];
        $captchaResponseKey = $_POST['g-recaptcha-response'];
        $csrfToken = $_POST["csrf_token"];

        if (! isset($csrfToken) || ! CsrfTokenManager::verifyToken($csrfToken)) {
            exit("CSRF Error. Request was blocked.");
        }

        if (! $this->captcha->validateCaptcha($captchaResponseKey)) {
            exit("Captcha validation failed.");
        }

        $validator = new Validator();

        if (empty($username) || empty($email) || empty($password) || empty($passwordConfirmation)) {
            exit("Username, email, password and password confirmation fields are required.");
        }

        $validator->validate([
            "username" => $username,
            "email" => $email,
            "password" => $password,
            "password_confirmation" => $passwordConfirmation
        ]);

        (new User)->register($username, $email, $password);

        header("Location: /login?register=success");
        exit();

        // exit("Registration failed.");
    }
}
