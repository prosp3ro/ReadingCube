<?php

declare(strict_types=1);

namespace Src\Controllers\Auth;

use PDOException;
use Src\Exceptions\DatabaseQueryException;
use Src\Helpers\Captcha;
use Src\Models\DB;
use Src\View;

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

    public function login(object $captcha)
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $captchaResponseKey = $_POST['g-recaptcha-response'];

        if (empty($email) || empty($password)) {
            exit("Email and password are required.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            exit("Email has invalid format");
        }

        if (!$captcha->validateCaptcha($captchaResponseKey)) {
            exit("Captcha validation failed.");
        }

        $sql = "SELECT * FROM users WHERE email = ?";
        $statement = $this->db->prepare($sql);

        try {
            $statement->execute([$email]);
            $user = $statement->fetch();
        } catch (PDOException $exception) {
            throw new DatabaseQueryException($exception->getMessage());
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

        exit("Email or password is incorrect.");
    }
}
