<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Exceptions\DatabaseQueryException;
use App\Helpers\Captcha;
use App\Helpers\CsrfTokenManager;
use App\Models\User;
use App\View;
use Throwable;
use Illuminate\Database\Capsule\Manager as DB;
use App\Validator;

class UserController
{
    private ?int $sessionUserId = null;
    private ?object $user = null;

    public function __construct(private $captcha = new Captcha(GOOGLE_RECAPTCHA_SITE_KEY, GOOGLE_RECAPTCHA_SECRET_KEY))
    {
        if (isset($_SESSION['user_id'])) {
            $this->sessionUserId = (int) $_SESSION["user_id"];

            try {
                $User = new User();

                $this->user = $User->getCurrentUser($this->sessionUserId);
            } catch (Throwable $exception) {
                throw new DatabaseQueryException($exception->getMessage());
            }
        }
    }

    public function showEditProfilePage()
    {
        if (!$this->sessionUserId) {
            header("Location: /login");
            exit();
        }

        $csrfToken = CsrfTokenManager::generateToken();

        $updateMessage = $_GET["update"] ?? "";

        return View::create("edit-profile", [
            "user" => $this->user,
            "captcha" => $this->captcha,
            "csrfToken" => $csrfToken,
            "updateMessage" => $updateMessage
        ])->render();
    }

    public function updateProfile()
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

        if (!$this->captcha->validateCaptcha($captchaResponseKey)) {
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
            $User = new User();

            $User->updateProfile($this->sessionUserId, $dataToUpdate);
        }

        header("Location: /edit-profile?update=data");
        exit();
    }

    // TODO add check if new password is same as old
    public function updatePassword()
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

        if (!$this->captcha->validateCaptcha($captchaResponseKey)) {
            exit("Captcha validation failed.");
        }

        if (empty($currentPassword) || empty($newPassword) || empty($newPasswordConfirmation)) {
            exit("Current password, new password and password confirmation are required.");
        }

        if (! $this->verifyPassword($currentPassword)) {
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

        $User = new User();
        $User->updatePassword($this->sessionUserId, $newPasswordHashed);

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

        return $user && $passwordVerified;
    }
}
