<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Captcha;
use App\Helpers\CsrfTokenManager;
use App\Models\User;
use App\View;
use App\Validator;

class UserController
{
    private ?object $user = null;

    public function __construct(private $captcha = new Captcha(GOOGLE_RECAPTCHA_SITE_KEY, GOOGLE_RECAPTCHA_SECRET_KEY))
    {
        $userId = $_SESSION["user_id"] ?? null;

        if (isset($userId)) {
            $this->user = (new User())->getCurrentUser((int) $userId);
        }
    }

    public function showEditProfilePage()
    {
        if (! $this->user) {
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
        if (! $this->user) {
            header("Location: /login");
            exit();
        }

        $User = new User();

        $newUsername = $_POST["newUsername"];
        $newEmail = $_POST["newEmail"];
        $password = $_POST["password"];
        $captchaResponseKey = $_POST["g-recaptcha-response"];
        $csrfToken = $_POST["csrf_token"];

        if (! isset($csrfToken) || ! CsrfTokenManager::verifyToken($csrfToken)) {
            exit("CSRF Error. Request was blocked.");
        }

        if (! $this->captcha->validateCaptcha($captchaResponseKey)) {
            exit("Captcha validation failed.");
        }

        if (empty($password)) {
            exit("Password is required.");
        }

        if (! $User->verifyPassword($this->user->id, $password)) {
            exit("Current password is incorrect.");
        }

        if (! $newUsername && ! $newEmail) {
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

        if (! empty($dataToUpdate)) {
            $User->updateProfile($this->user->id, $dataToUpdate);
        }

        header("Location: /edit-profile?update=data");
        exit();
    }

    // TODO add check if new password is same as old
    public function updatePassword()
    {
        if (! $this->user->id) {
            header("Location: /login");
            exit();
        }

        $User = new User();

        $currentPassword = $_POST["current_password"];
        $newPassword = $_POST["new_password"];
        $newPasswordConfirmation = $_POST["new_password_confirmation"];
        $captchaResponseKey = $_POST["g-recaptcha-response"];
        $csrfToken = $_POST["csrf_token"];

        if (! isset($csrfToken) || !CsrfTokenManager::verifyToken($csrfToken)) {
            exit("CSRF Error. Request was blocked.");
        }

        if (! $this->captcha->validateCaptcha($captchaResponseKey)) {
            exit("Captcha validation failed.");
        }

        if (empty($currentPassword) || empty($newPassword) || empty($newPasswordConfirmation)) {
            exit("Current password, new password and password confirmation are required.");
        }

        if (! $User->verifyPassword($this->user->id, $currentPassword)) {
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

        $User->updatePassword($this->user->id, $newPasswordHashed);

        header("Location: /edit-profile?update=pwd");
        exit();
    }
}
