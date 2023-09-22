<?php

declare(strict_types=1);

namespace Src\Controller\Auth;

use Src\Exception\DatabaseQueryException;
use Src\Model\DB;
use Src\Model\User;
use Src\View;

class RegisterController
{
    private View $view;
    private DB $db;

    public function __construct(View $view, DB $db)
    {
        $this->view = $view;
        $this->db = $db;
    }

    private function createUser(User $user): bool
    {
        $sql = "INSERT INTO users(username, email, password) VALUES (?, ?, ?)";

        try {
            $statement = $this->db->prepare($sql);
            $hashedPassword = password_hash($user->getPassword(), PASSWORD_BCRYPT, [
                "cost" => 12
            ]);

            return $statement->execute([$user->getUsername(), $user->getEmail(), $hashedPassword]);
        } catch (\Throwable $exception) {
            throw new DatabaseQueryException($exception->getMessage());
        }
    }

    private function isEmailUnique(string $email): bool
    {
        $sql = "SELECT COUNT(*) FROM users WHERE email = ?";

        try {
            $statement = $this->db->prepare($sql);
            $statement->execute([$email]);
            return (int)$statement->fetchColumn() > 0;
        } catch (\Throwable $exception) {
            throw new DatabaseQueryException($exception->getMessage());
        }
    }

    private function isUsernameUnique(string $username): bool
    {
        $sql = "SELECT COUNT(*) FROM users WHERE username = ?";

        try {
            $statement = $this->db->prepare($sql);
            $statement->execute([$username]);
            return (int)$statement->fetchColumn() > 0;
        } catch (\Throwable $exception) {
            throw new DatabaseQueryException($exception->getMessage());
        }
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
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $passwordConfirmation = $_POST['password_confirmation'];

        if (empty($email) || empty($password) || empty($username)) {
            exit("Username, email and password fields are required.");
        }

        if (!preg_match('/^[a-zA-Z0-9]{5,}$/', $username)) {
            exit("Invalid username format. Please use only letters and numbers, and ensure it's at least 5 characters long.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            exit("Email has invalid format");
        }

        if (!preg_match("/^(?=.*[a-z])(?=.*[0-9]).{8,}$/i", $password)) {
            exit("Password must be at least 8 characters and contain at least one letter and one number.");
        }

        if ($password !== $passwordConfirmation) {
            exit("Passwords must match.");
        }

        $user = new User($username, $email, $password);

        if ($this->isUsernameUnique($username)) {
            exit("Username is already taken.");
        }

        if ($this->isEmailUnique($email)) {
            exit("Email is already taken.");
        }

        if ($this->createUser($user)) {
            header("Location: /login?register=success");
            exit();
        } else {
            exit("Registration failed.");
        }
    }
}
