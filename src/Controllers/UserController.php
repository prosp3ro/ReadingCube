<?php

declare(strict_types=1);

namespace Src\Controllers;

use PDOException;
use Src\Exceptions\DatabaseQueryException;
use Src\Models\DB;
use Src\View;
use Throwable;

class UserController
{
    private View $view;
    private DB $db;

    public function __construct(View $view, DB $db)
    {
        $this->view = $view;
        $this->db = $db;
    }

    public function showEditProfilePage()
    {
        // check if user is logged in - middleware?
        if (isset($_SESSION['user_id'])) {
            $sessionUserId = $_SESSION["user_id"];
            $sql = "SELECT * FROM users WHERE id = ?";
            $statement = $this->db->prepare($sql);

            try {
                $statement->execute([$sessionUserId]);
                $userData = $statement->fetch();

                return $this->view->render("edit-profile", [
                    "userData" => $userData
                ]);
            } catch (Throwable $exception) {
                throw new DatabaseQueryException($exception->getMessage());
                exit();
            }
        }

        header("Location: /login");
        exit();
    }

    public function editProfileData(object $captcha)
    {
        $newUsername = $_POST["newUsername"] ?? null;
        $newEmail = $_POST["newEmail"] ?? null;
        $password = $_POST["password"];
        $captchaResponseKey = $_POST["g-recaptcha-response"];
        $sessionUserId = $_SESSION["user_id"];

        if (empty($password)) {
            exit("Password is required.");
        }

        // if (!$captcha->validateCaptcha($captchaResponseKey)) {
        //     exit("Captcha validation failed.");
        // }

        if (!$this->verifyPassword($sessionUserId, $password)) {
            exit("Password is incorrect.");
        }

        if ($newUsername && $newEmail) {
            // validate newUsername
            if (!preg_match('/^[a-zA-Z0-9]{5,}$/', $newUsername)) {
                exit("Invalid newUsername format. Please use only letters and numbers, and ensure it's at least 5 characters long.");
            }

            // check if newUsername is available

            // validate email
            if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
                exit("Email has invalid format");
            }

            // check if email is available

            // update newUsername and email in database
            $updateSql = "UPDATE users SET newUsername = ?, email = ? WHERE id = ?";
            $statement = $this->db->prepare($updateSql);

            try {
                $statement->execute([$newUsername, $newEmail, $sessionUserId]);
            } catch (Throwable $exception) {
                throw new DatabaseQueryException($exception->getMessage());
                exit();
            }
        } else if ($newEmail) {
            // validate email

            // update email in database
            $updateSql = "UPDATE users SET email = ? WHERE id = ?";
            $statement = $this->db->prepare($updateSql);

            try {
                $statement->execute([$newEmail, $sessionUserId]);
            } catch (Throwable $exception) {
                throw new DatabaseQueryException($exception->getMessage());
                exit();
            }
        } else if ($newUsername) {
            // validate newUsername
            $this->validateUsername($newUsername);

            // update newUsername in database
            $updateSql = "UPDATE users SET newUsername = ? WHERE id = ?";
            $statement = $this->db->prepare($updateSql);

            try {
                $statement->execute([$newUsername, $sessionUserId]);
            } catch (Throwable $exception) {
                throw new DatabaseQueryException($exception->getMessage());
                exit();
            }
        }

        header("Location: /edit-profile?edit=success");
        exit();
    }

    // TODO rewrite
    private function verifyPassword(int $sessionUserId, string $password): bool
    {
        $sql = "SELECT * FROM users WHERE id = ?";
        $statement = $this->db->prepare($sql);

        try {
            $statement->execute([$sessionUserId]);
            $user = $statement->fetch();
        } catch (PDOException $exception) {
            throw new DatabaseQueryException($exception->getMessage());
        }

        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }

        return true;
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

    private function validateEmail(string $newEmail)
    {
        if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            exit("Email has invalid format");
        }

        $json = $this->validate("email", $newEmail);
        $jsonArray = json_decode($json, true);

        if ($jsonArray["available"] == false) {
            exit("Email is already taken.");
        }
    }

    private function validateUsername(string $newUsername)
    {
        if (!preg_match('/^[a-zA-Z0-9]{5,}$/', $newUsername)) {
            exit("Invalid username format. Please use only letters and numbers, and ensure it's at least 5 characters long.");
        }

        $json = $this->validate("username", $newUsername);
        $jsonArray = json_decode($json, true);

        if ($jsonArray["available"] == false) {
            exit("Username is already taken.");
        }
    }
}
