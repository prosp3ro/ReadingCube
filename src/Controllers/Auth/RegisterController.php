<?php

declare(strict_types=1);

namespace Src\Controllers\Auth;

use Src\Exceptions\DatabaseQueryException;
use Src\Models\DB;
use Src\Models\User;
use Src\View;
use Throwable;

class RegisterController
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
        $email = $_GET["email"] ?? "";

        if (!empty($email)) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo $this->validate("email", $email);
                exit();
            } else {
                exit("Email has invalid format");
            }
        }

        return $this->view->render("auth/register", [
            "header" => "Register | " . APP_NAME
        ]);
    }

    public function register(object $captcha): void
    {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $passwordConfirmation = $_POST['password_confirmation'];
        $captchaResponseKey = $_POST['g-recaptcha-response'];

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

        $validationResult = $captcha->validateCaptcha($captchaResponseKey);

        if (!is_object($validationResult) || !property_exists($validationResult, 'success') || !$validationResult->success) {
            exit("Captcha verification failed.");
        }

        $user = new User($username, $email, $password);

        $this->validateEmail($email);
        $this->validateUsername($username);

        if ($this->createUser($user)) {
            header("Location: /login?register=success");
            exit();
        } else {
            exit("Registration failed.");
        }
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

    private function validate(string $type, string $data)
    {
        $sql = "SELECT COUNT(*) FROM users WHERE {$type} = ?";

        try {
            $statement = $this->db->prepare($sql);
            $statement->execute([$data]);

            header("Content-Type: application/json");

            $jsonData = json_encode([
                "available" => (int) $statement->fetchColumn() == 0
            ]);

            return $jsonData;
            exit();
        } catch (Throwable $exception) {
            throw new DatabaseQueryException($exception->getMessage());
        }
    }

    private function validateEmail(string $email)
    {
        $json = $this->validate("email", $email);
        $jsonArray = json_decode($json, true);

        if ($jsonArray["available"] == false) {
            exit("Email is already taken.");
        }
    }

    private function validateUsername(string $username)
    {
        $json = $this->validate("username", $username);
        $jsonArray = json_decode($json, true);

        if ($jsonArray["available"] == false) {
            exit("Username is already taken.");
        }
    }
}