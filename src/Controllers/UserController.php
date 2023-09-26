<?php

declare(strict_types=1);

namespace Src\Controllers;

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
        $newUsername = $_POST['newUsername'] ?? null;
        $newEmail = $_POST['newEmail'] ?? null;
        $password = $_POST['password'];
        $captchaResponseKey = $_POST['g-recaptcha-response'];

        if (empty($password)) {
            exit("Password is required.");
        }

        $validationResult = $captcha->validateCaptcha($captchaResponseKey);

        if (!is_object($validationResult) || !property_exists($validationResult, 'success') || !$validationResult->success) {
            exit("Captcha verification failed.");
        }

        if ($newUsername && $newEmail) {
            if (!preg_match('/^[a-zA-Z0-9]{5,}$/', $newUsername)) {
                exit("Invalid username format. Please use only letters and numbers, and ensure it's at least 5 characters long.");
            }

            if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
                exit("Email has invalid format");
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
