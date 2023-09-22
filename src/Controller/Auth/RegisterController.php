<?php

declare(strict_types=1);

namespace Src\Controller\Auth;

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

    public function index(bool $emailAlreadyUsed = false)
    {
        return $this->view->render("auth/register", [
            "header" => "Register | " . APP_NAME,
            "emailAlreadyUsed" => $emailAlreadyUsed
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
        exit();

        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $passwordConfirmation = $_POST['password_confirmation'];

        if (empty($email) || empty($password) || empty($username)) {
            exit("");
        }

        if (!preg_match('/^[a-zA-Z0-9]{5,}$/', $username)) {
            exit("Invalid username format. Please use only letters and numbers, and ensure it's at least 5 characters long.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            exit("Valid email is required!");
        }

        if (!preg_match("/^(?=.*[a-z])(?=.*[0-9]).{8,}$/i", $password)) {
            exit("Password must be at least 8 characters and contain at least one letter and one number");
        }

        // if (strlen($password) < 8) {
        //     exit("Password must be at least 8 characters");
        // }

        // if (!preg_match("/[a-z]/i", $password)) {
        //     exit("Password must contain at least one letter");
        // }

        // if (!preg_match("/[0-9]/", $password)) {
        //     exit("Password must contain at least one number");
        // }

        if ($password !== $passwordConfirmation) {
            exit("Passwords must match");
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT, [
            "cost" => 12
        ]);

        $sql = "INSERT INTO users(username, email, password)
                    VALUES (?, ?, ?)";

        $statement = $this->db->prepare($sql);

        try {
            $statement->execute([$username, $email, $hashedPassword]);
            header("Location: /register-success");
            exit();
        } catch (Throwable $exception) {
            if ($exception->getCode() == "23000" && strpos($exception->getMessage(), 'Duplicate entry') !== false) {
                echo "Username or email already in use";

                // $this->index(true);
            } else {
                throw new DatabaseQueryException($exception->getMessage());
            }

            exit();
        }
    }
}
