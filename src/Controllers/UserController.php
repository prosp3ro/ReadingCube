<?php

declare(strict_types=1);

namespace Src\Controllers;

use Src\Exceptions\DatabaseQueryException;
use Src\Helpers\Captcha;
use Src\Helpers\CsrfTokenManager;
use Src\View;
use Throwable;
use Illuminate\Database\Capsule\Manager as DB;
use Src\Validator;

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

        if (!$newUsername && !$newEmail) {
            header("Location: /edit-profile");
            exit;
        }

        $validator = new Validator();

        $dataToUpdate = [];

        if ($newUsername) {
            $validator->validate(["username" => $newUsername]);
            $dataToUpdate["username"] = $newUsername;
        }

        if ($newEmail) {
            $validator->validate(["email" => $newEmail]);
            $dataToUpdate["email"] = $newEmail;
        }

        if (!empty($dataToUpdate)) {
            DB::table("users")
                ->where("id", "=", $this->sessionUserId)
                ->update($dataToUpdate);
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

        if (empty($currentPassword) || empty($newPassword) || empty($newPasswordConfirmation)) {
            exit("Current password, new password and password confirmation are required.");
        }

        if (!$this->verifyPassword($currentPassword)) {
            exit("Current password is incorrect.");
        }

        $validator = new Validator();

        $validator->validate([
            "password" => $newPassword,
            "password_confirmation" => $newPasswordConfirmation
        ]);

        $newPasswordHashed = password_hash($newPassword, PASSWORD_BCRYPT, [
            "cost" => 12
        ]);

        DB::table("users")
            ->where("id", "=", $this->sessionUserId)
            ->update([
                "password" => $newPasswordHashed
            ]);

        header("Location: /edit-profile?update=pwd");
        exit();
    }

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

        $passwordVerified = password_verify($password, $user->password);

        return !$user || !$passwordVerified;
    }
}
