<?php

declare(strict_types=1);

namespace Src\Controllers\Auth;

use SimpleCaptcha\Builder;
use Src\Exceptions\DatabaseQueryException;
use Src\Models\DB;
use Src\View;
use Throwable;

class LoginController
{
    private View $view;
    private DB $db;

    public function __construct(View $view, DB $db)
    {
        $this->view = $view;
        $this->db = $db;
    }

    public function index()
    {
        if (isset($_SESSION['user_id'])) {
            header("Location: /");
            exit();
        }

        return $this->view->render("auth/login", [
            "header" => "Login | " . APP_NAME,
        ]);
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
        $captchaPrivateKey = GOOGLE_RECAPTCHA_PRIVATE_KEY;
        $captchaResponseKey = $_POST['g-recaptcha-response'];

        if (empty($email) || empty($password)) {
            exit("Email and password are required.");
        }

        $captchaVerificationUrl = 'https://www.google.com/recaptcha/api/siteverify';
        $captchaData = [
            'secret' => $captchaPrivateKey,
            'response' => $captchaResponseKey
        ];

        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($captchaData)
            ]
        ];

        $context = stream_context_create($options);
        $captchaVerificationResult = file_get_contents($captchaVerificationUrl, false, $context);
        $jsonResult = json_decode($captchaVerificationResult);

        if (!$jsonResult->success) {
            exit("Captcha verification failed.");
        }

        $sql = "SELECT * FROM users WHERE email = ?";
        $statement = $this->db->prepare($sql);

        try {
            $statement->execute([$email]);
            $user = $statement->fetch();
        } catch (Throwable $exception) {
            throw new DatabaseQueryException($exception->getMessage());
            // throw new DatabaseQueryException($exception->getMessage());
            exit();
        }

        if ($user) {
            if (password_verify($password, $user['password'])) {
                session_start();
                session_regenerate_id();
                $_SESSION["user_id"] = $user["id"];

                header("Location: /");
                exit();
            }
        }

        exit("Login failed.");
    }
}
