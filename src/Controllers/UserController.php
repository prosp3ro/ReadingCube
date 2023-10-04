<?php

declare(strict_types=1);

namespace Src\Controllers;

use Src\Exceptions\DatabaseQueryException;
use Src\Helpers\Captcha;
use Src\Helpers\CsrfTokenManager;
use Src\View;
use Throwable;
use Illuminate\Database\Capsule\Manager as DB;

class UserController
{
    private View $view;
    private ?int $sessionUserId = null;
    private ?object $user = null;

    public function __construct(View $view)
    {
        $this->view = $view;

        if (isset($_SESSION['user_id'])) {
            $this->sessionUserId = (int) $_SESSION["user_id"];

            try {
                $this->user = DB::table("users")
                    ->where("id", "=", $this->sessionUserId)
                    ->first();
            } catch (Throwable $exception) {
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

        $updateMessage = $_GET["update"] ?? "";

        return $this->view->render("edit-profile", [
            "user" => $this->user,
            "captcha" => $captcha,
            "csrfToken" => $csrfToken,
            "updateMessage" => $updateMessage
        ]);
    }

    public function updateProfile(object $captcha)
    {
        if (!$this->sessionUserId) {
            header("Location: /login");
            exit();
        }

        $newUsername = $_POST["newUsername"];
        $newEmail = $_POST["newEmail"];
        $password = $_POST["password"];
        $captchaResponseKey = $_POST["g-recaptcha-response"];
        $csrfToken = $_POST["csrf_token"];

        if (!isset($csrfToken) || !CsrfTokenManager::verifyToken($csrfToken)) {
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
        } else {
            header("Location: /edit-profile");
        }

        header("Location: /edit-profile?update=data");
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

        if (!isset($csrfToken) || !CsrfTokenManager::verifyToken($csrfToken)) {
            exit("CSRF Error. Request was blocked.");
        }

        if (!$captcha->validateCaptcha($captchaResponseKey)) {
            exit("Captcha validation failed.");
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

        if (!$this->verifyPassword($currentPassword)) {
            exit("Current password is incorrect.");
        }

        $passwordRegex = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/m";

        if (!preg_match($passwordRegex, $newPassword)) {
            exit("Password must be at least 8 characters and contain at least 1 uppercase letter, 1 lowercase letter, and 1 number.");
        }

        if ($newPassword !== $newPasswordConfirmation) {
            exit("Passwords must match.");
        }

        $newPasswordHashed = password_hash($newPassword, PASSWORD_BCRYPT, [
            "cost" => 12
        ]);

        try {
            DB::table("users")
                ->where("id", "=", $this->sessionUserId)
                ->update([
                    "password" => $newPasswordHashed
                ]);
        } catch (Throwable $exception) {
            throw new DatabaseQueryException($exception->getMessage());
        }

        header("Location: /edit-profile?update=pwd");
        exit();
    }

    // TODO rewrite
    private function verifyPassword(string $password): bool
    {
        try {
            $user = DB::table("users")
                ->select("password", "id")
                ->where("id", "=", $this->sessionUserId)
                ->first();
        } catch (Throwable $exception) {
            throw new DatabaseQueryException($exception->getMessage());
        }

        if (!$user || !password_verify($password, $user->password)) {
            return false;
        }

        return true;
    }

    private function validate(string $dataType, string $newData)
    {
        $isNotAvailable = DB::table("users")
            ->where($dataType, "=", $newData)
            ->count();

        header("Content-Type: application/json");

        $jsonData = json_encode([
            "available" => (int) $isNotAvailable == 0
        ]);

        return $jsonData;
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
        $usernameRegex = "/^[a-zA-Z0-9]{5,}$/";

        // TODO dont exit here
        if (!preg_match($usernameRegex, $newUsername)) {
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
        try {
            DB::table("users")
                ->where("id", "=", $this->sessionUserId)
                ->update([
                    $dataType => $newData
                ]);
        } catch (Throwable $exception) {
            throw new DatabaseQueryException($exception->getMessage());
        }
    }
}
