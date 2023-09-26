<?php

declare(strict_types=1);

namespace Src\Controllers;

use PDOException;
use Src\Exceptions\DatabaseQueryException;
use Src\Helpers\Captcha;
use Src\Helpers\CsrfTokenManager;
use Src\Models\DB;
use Src\View;
use Throwable;

class UserController
{
    private View $view;
    private DB $db;
    private ?int $sessionUserId = null;
    private ?array $user;

    public function __construct(View $view, DB $db)
    {
        $this->view = $view;
        $this->db = $db;

        if (isset($_SESSION['user_id'])) {
            $this->sessionUserId = $_SESSION["user_id"];

            $sql = "SELECT * FROM users WHERE id = ?";
            $statement = $this->db->prepare($sql);

            try {
                $statement->execute([$this->sessionUserId]);
                $this->user = $statement->fetch();
            } catch (PDOException $exception) {
                throw new DatabaseQueryException($exception->getMessage());
            }
        }
    }

    public function showEditProfilePage(object $captcha)
    {
        if (!$this->sessionUserId) {
            header("Location: /login");
            exit();
        }

        $csrfToken = CsrfTokenManager::generateToken();

        return $this->view->render("edit-profile", [
            "user" => $this->user,
            "captcha" => $captcha,
            "csrfToken" => $csrfToken
        ]);
    }

    public function updateProfile(object $captcha)
    {
        if (!$this->sessionUserId) {
            header("Location: /login");
            exit();
        }

        $newUsername = $_POST["newUsername"] ?? null;
        $newEmail = $_POST["newEmail"] ?? null;
        $password = $_POST["password"];
        $captchaResponseKey = $_POST["g-recaptcha-response"];
        $csrfToken = $_POST["csrf_token"];

        if (!$this->verifyCsrfToken($csrfToken)) {
            exit("CSRF Error. Request was blocked.");
        }

        if (!$captcha->validateCaptcha($captchaResponseKey)) {
            exit("Captcha validation failed.");
        }

        if (empty($password)) {
            exit("Password is required.");
        }

        if (!$this->verifyPassword($password)) {
            exit("Password is incorrect.");
        }

        // TODO rewrite
        if ($newUsername && $newEmail) {
            $this->validateUsername($newUsername);
            $this->validateEmail($newEmail);

            $this->updateData("username", $newUsername);
            $this->updateData("email", $newEmail);
        } else if ($newEmail) {
            $this->validateEmail($newEmail);
            $this->updateData("email", $newEmail);
        } else if ($newUsername) {
            $this->validateUsername($newUsername);
            $this->updateData("username", $newUsername);
        }

        header("Location: /edit-profile?edit=success");
        exit();
    }

    // TODO add check if new password is same as old
    public function updatePassword(Captcha $captcha)
    {
        if (!$this->sessionUserId) {
            header("Location: /login");
            exit();
        }

        $currentPassword = $_POST["current_password"];
        $newPassword = $_POST["new_password"];
        $newPasswordConfirmation = $_POST["new_password_confirmation"];
        $captchaResponseKey = $_POST["g-recaptcha-response"];
        $csrfToken = $_POST["csrf_token"];

        if (!$this->verifyCsrfToken($csrfToken)) {
            exit("CSRF Error. Request was blocked.");
        }

        if (empty($currentPassword)) {
            exit("Current password is required.");
        }

        if (empty($newPassword)) {
            exit("New password is required.");
        }

        if (empty($newPasswordConfirmation)) {
            exit("Password confirmation is required.");
        }

        if (!$captcha->validateCaptcha($captchaResponseKey)) {
            exit("Captcha validation failed.");
        }

        if (!$this->verifyPassword($currentPassword)) {
            exit("Current password is incorrect.");
        }

        if (!preg_match("/^(?=.*[a-z])(?=.*[0-9]).{8,}$/i", $newPassword)) {
            exit("Password must be at least 8 characters and contain at least one letter and one number.");
        }

        if ($newPassword !== $newPasswordConfirmation) {
            exit("Passwords must match.");
        }

        $updatePasswordSql = "UPDATE users SET password = ? WHERE id = ?";

        try {
            $statement = $this->db->prepare($updatePasswordSql);
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT, [
                "cost" => 12
            ]);

            $statement->execute([$hashedPassword, $this->sessionUserId]);
        } catch (PDOException $exception) {
            throw new DatabaseQueryException($exception->getMessage());
        }

        header("Location: /edit-profile?updatepwd=success");
        exit();
    }

    // TODO rewrite
    private function verifyPassword(string $password): bool
    {
        $sql = "SELECT * FROM users WHERE id = ?";
        $statement = $this->db->prepare($sql);

        try {
            $statement->execute([$this->sessionUserId]);
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
        } catch (Throwable $exception) {
            throw new DatabaseQueryException($exception->getMessage());
        }
    }

    // TODO rewrite
    private function validateEmail(string $newEmail)
    {
        // TODO dont exit here
        if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            exit("Email has invalid format");
        }

        $json = $this->validate("email", $newEmail);
        $jsonArray = json_decode($json, true);

        if ($jsonArray["available"] == false) {
            exit("Email is already taken.");
        }
    }

    // TODO rewrite
    private function validateUsername(string $newUsername)
    {
        // TODO dont exit here
        if (!preg_match('/^[a-zA-Z0-9]{5,}$/', $newUsername)) {
            exit("Invalid username format. Please use only letters and numbers, and ensure it's at least 5 characters long.");
        }

        $json = $this->validate("username", $newUsername);
        $jsonArray = json_decode($json, true);

        if ($jsonArray["available"] == false) {
            exit("Username is already taken.");
        }
    }

    // TODO datatype should be an array and this method should allow for many types
    private function updateData(string $dataType, string $newData)
    {
        $updateSql = "UPDATE users SET {$dataType} = ? WHERE id = ?";
        $statement = $this->db->prepare($updateSql);

        try {
            $statement->execute([$newData, $this->sessionUserId]);
        } catch (Throwable $exception) {
            throw new DatabaseQueryException($exception->getMessage());
            exit();
        }
    }

    private function verifyCsrfToken(string $csrfToken): bool
    {
        if (isset($csrfToken) && CsrfTokenManager::verifyToken($csrfToken)) {
            return true;
        }

        return false;
    }
}
