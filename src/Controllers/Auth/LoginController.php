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

        $captcha = new Builder();
        $captcha->build(300, 100);
        $captcha->bgColor = "#131313";
        $captcha->distort = false;
        $captcha->applyEffects = false;

        return $this->view->render("auth/login", [
            "header" => "Login | " . APP_NAME,
            "captcha" => $captcha
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

        if (empty($email) || empty($password)) {
            exit("Email and password are required.");
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
