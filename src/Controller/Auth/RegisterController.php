<?php

declare(strict_types=1);

namespace Src\Controller\Auth;

use PDOException;
use Src\Exception\DatabaseQueryException;
use Src\Model\DB;
use Src\View;
use Throwable;

class RegisterController
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
        return $this->view->render("auth/register", [
            "header" => "Register | " . APP_NAME
        ]);
    }

    public function registerSuccess()
    {
        return $this->view->render("auth/register-success", [
            "header" => "Registration successful | " . APP_NAME
        ]);
    }

    public function register()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $passwordConfirmation = $_POST['password_confirmation'];

        if (empty($email) || empty($password)) {
            exit("Email and password are required");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            exit("Valid email is required!");
        }

        if (strlen($password) < 8) {
            exit("Password must be at least 8 characters");
        }

        if (!preg_match("/[a-z]/i", $password)) {
            exit("Password must contain at least one letter");
        }

        if (!preg_match("/[0-9]/", $password)) {
            exit("Password must contain at least one number");
        }

        if ($password !== $passwordConfirmation) {
            exit("Passwords must match");
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO users(email, password)
                    VALUES (?, ?)";

        $statement = $this->db->prepare($sql);

        try {
            $statement->execute([$email, $hashedPassword]);
            header("Location: /register-success");
            exit();
        } catch (Throwable $exception) {
            if ($exception->getCode() == "23000" && strpos($exception->getMessage(), 'Duplicate entry') !== false) {
                echo "Email already in use";
            } else {
                throw new DatabaseQueryException($exception->getMessage());
            }

            exit;
        }

        // header("Location: /login");
    }
}
