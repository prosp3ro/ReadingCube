<?php

declare(strict_types=1);

namespace Src\Controller\Auth;

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

    public function index()
    {
        return $this->view->render("auth/login", [
            "header" => "Login | " . APP_NAME
        ]);
    }

    public function login()
    {
        $email = $_POST['email'];
        $email = $this->db->quote($email);

        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            exit("Email and password are required");
        }

        $sql = sprintf("SELECT * FROM users WHERE email = %s", $email);

        $statement = $this->db->prepare($sql);

        try {
            $statement->execute();
            $user = $statement->fetch();
            dd($user);
            die();
            header("Location: /");
        } catch (Throwable $exception) {
            dd($exception);
            die();
        }
    }
}
