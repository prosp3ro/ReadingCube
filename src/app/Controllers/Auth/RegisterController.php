<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use Illuminate\Database\Capsule\Manager as DB;
use App\Exceptions\DatabaseQueryException;
use App\Helpers\Captcha;
use App\Helpers\CsrfTokenManager;
use App\Validator;
use App\View;

class RegisterController
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

        $csrfToken = CsrfTokenManager::generateToken();

        // TODO it should be done differently
        // $email = $_GET["email"] ?? "";
        // $validator = new Validator();
        // $validator->isEmailAvailableJson($email);

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

        if (!isset($csrfToken) || !CsrfTokenManager::verifyToken($csrfToken)) {
            exit("CSRF Error. Request was blocked.");
        }

        if (!$this->captcha->validateCaptcha($captchaResponseKey)) {
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

        // $user = User::Create([
        //     'username' => $username,
        //     'email' => $email,
        //     'password' => password_hash($password, PASSWORD_BCRYPT)
        // ]);

        DB::beginTransaction();

        try {
            DB::table('users')->insert([
                'username' => $username,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_BCRYPT)
            ]);

            DB::commit();

            header("Location: /login?register=success");
            exit();
        } catch (\Throwable $exception) {
            // if (DB::inTransaction()) {
            DB::rollback();
            // }

            throw new DatabaseQueryException('Registration failed: ' . $exception->getMessage());
        }

        exit("Registration failed.");
    }
}
