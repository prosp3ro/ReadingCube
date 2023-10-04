<?php

declare(strict_types=1);

namespace Src\Controllers\Auth;

use Illuminate\Database\Capsule\Manager as DB;
use Src\Helpers\CsrfTokenManager;
use Src\Models\User;
use Src\Validator;
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
                echo $this->isUnique("email", $email);
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

        $validator = new Validator();

        if (empty($username) || empty($email) || empty($password) || empty($passwordConfirmation)) {
            exit("Username, email, password and password confirmation fields are required.");
        }

        $validator->validate([
            "username" => $username,
            "email" => $email,
            "password" => $password,
        ]);

        $this->isEmailUnique($email);
        $this->isUsernameUnique($username);

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

    private function isUnique(string $type, string $data)
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

    private function isEmailUnique(string $email)
    {
        $json = $this->isUnique("email", $email);
        $jsonArray = json_decode($json, true);

        if ($jsonArray["available"] == false) {
            exit("Email is already taken.");
        }
    }

    private function isUsernameUnique(string $username)
    {
        $json = $this->isUnique("username", $username);
        $jsonArray = json_decode($json, true);

        if ($jsonArray["available"] == false) {
            exit("Username is already taken.");
        }
    }
}
