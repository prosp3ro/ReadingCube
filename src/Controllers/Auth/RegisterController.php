<?php

declare(strict_types=1);

namespace Src\Controllers\Auth;

use Illuminate\Database\Capsule\Manager as DB;
use Src\Helpers\CsrfTokenManager;
use Src\Models\User;
use Src\View;

class RegisterController
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

        $csrfToken = CsrfTokenManager::generateToken();
        $email = $_GET["email"] ?? "";

        if (!empty($email)) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo $this->validate("email", $email);
                exit();
            } else {
                exit("Email has invalid format");
            }
        }

        return $this->view->render("auth/register", [
            "header" => "Register | " . APP_NAME,
            "captcha" => $captcha,
            "csrfToken" => $csrfToken
        ]);
    }

    public function register(object $captcha): void
    {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $passwordConfirmation = $_POST['password_confirmation'];
        $captchaResponseKey = $_POST['g-recaptcha-response'];
        $csrfToken = $_POST["csrf_token"];

        if (!isset($csrfToken) || !CsrfTokenManager::verifyToken($csrfToken)) {
            exit("CSRF Error. Request was blocked.");
        }

        if (!$captcha->validateCaptcha($captchaResponseKey)) {
            exit("Captcha validation failed.");
        }

        if (empty($username) || empty($email) || empty($password) || empty($passwordConfirmation)) {
            exit("Username, email, password and password confirmation fields are required.");
        }

        $usernameRegex = "/^[a-zA-Z0-9]{5,}$/";

        if (!preg_match($usernameRegex, $username)) {
            exit("Invalid username format. Please use only letters and numbers, and ensure it's at least 5 characters long.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            exit("Email has invalid format");
        }

        $passwordRegex = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/m";

        if (!preg_match($passwordRegex, $password)) {
            exit("Password must be at least 8 characters and contain at least 1 uppercase letter, 1 lowercase letter, and 1 number.");
        }

        if ($password !== $passwordConfirmation) {
            exit("Passwords must match.");
        }

        $this->validateEmail($email);
        $this->validateUsername($username);

        $user = User::Create([
            'username' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT)
        ]);

        if ($user) {
            header("Location: /login?register=success");
            exit();
        } else {
            exit("Registration failed.");
        }
    }

    private function validate(string $type, string $data)
    {
        $isNotAvailable = DB::table("users")
            ->where($type, "=", $data)
            ->count();

        header("Content-Type: application/json");

        $jsonData = json_encode([
            "available" => (int) $isNotAvailable == 0
        ]);

        return $jsonData;
    }

    private function validateEmail(string $email)
    {
        $json = $this->validate("email", $email);
        $jsonArray = json_decode($json, true);

        if ($jsonArray["available"] == false) {
            exit("Email is already taken.");
        }
    }

    private function validateUsername(string $username)
    {
        $json = $this->validate("username", $username);
        $jsonArray = json_decode($json, true);

        if ($jsonArray["available"] == false) {
            exit("Username is already taken.");
        }
    }
}
