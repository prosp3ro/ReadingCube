<?php

declare(strict_types=1);

namespace Src\Controller\Auth;

use Src\Exception\DatabaseQueryException;
use Src\Model\DB;
use Src\View;
use Throwable;

class LoginController
{
    private View $view;
    private DB $db;

    public function __construct()
    {
        $this->view = new View();
        $this->db = new DB();
    }

    public function index(string $errorMessage = null)
    {
        return $this->view->render("auth/login", [
            "header" => "Login | " . APP_NAME,
            "errorMessage" => $errorMessage
        ]);
    }

    public function login()
    {
        $email = $_POST['email'];

        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            return $this->index("Email and password are required");
        }

        $sql = "SELECT * FROM users WHERE email = ?";
        $statement = $this->db->prepare($sql);

        try {
            $statement->execute([$email]);
            $user = $statement->fetch();
        } catch (Throwable $exception) {
            throw new DatabaseQueryException($exception->getMessage());
            exit();
        }

        if ($user) {
            if (password_verify($password, $user['password'])) {
                dd("password matches");
                die();
            }
        }

        return $this->index("Invalid login");
    }
}
