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

        if (!$captcha->validateCaptcha($captchaResponseKey)) {
            exit("Captcha validation failed.");
        }

        if ($newUsername && $newEmail) {
            // validate username
            if (!preg_match('/^[a-zA-Z0-9]{5,}$/', $newUsername)) {
                exit("Invalid username format. Please use only letters and numbers, and ensure it's at least 5 characters long.");
            }

            // validate email
            if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
                exit("Email has invalid format");
            }

            // verify password
            $sql = "SELECT * FROM users WHERE id = ?";
            $statement = $this->db->prepare($sql);

            try {
                $statement->execute([$sessionUserId]);
                $user = $statement->fetch();
            } catch (PDOException $exception) {
                throw new DatabaseQueryException($exception->getMessage());
            }

            if (!$user || !password_verify($password, $user['password'])) {
                exit("...");
            }

            // update username and email in database
            $updateSql = "UPDATE users SET username = ?, email = ? WHERE id = ?";
            $statement = $this->db->prepare($updateSql);

            try {
                $statement->execute([$newUsername, $newEmail, $sessionUserId]);
            } catch (Throwable $exception) {
                throw new DatabaseQueryException($exception->getMessage());
                exit();
            }
        } else if ($newEmail) {
            if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
                exit("Email has invalid format");
            }
        } else if ($newUsername) {
            if (!preg_match('/^[a-zA-Z0-9]{5,}$/', $newUsername)) {
                exit("Invalid username format. Please use only letters and numbers, and ensure it's at least 5 characters long.");
            }
        }

        header("Location: /edit-profile?edit=success");
        exit();
    }
}
