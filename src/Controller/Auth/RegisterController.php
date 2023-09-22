<?php

declare(strict_types=1);

namespace Src\Controller\Auth;

use Src\Model\User;
use Src\Model\UserRepository;
use Src\View;

class RegisterController
{
    private View $view;
    private UserRepository $userRepository;

    public function __construct(View $view, UserRepository $userRepository)
    {
        $this->view = $view;
        $this->userRepository = $userRepository;
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

        if ($this->userRepository->isUsernameUnique($username)) {
            exit("Username is already taken.");
        }

        if ($this->userRepository->isEmailUnique($email)) {
            exit("Email is already taken.");
        }

        if ($this->userRepository->createUser($user)) {
            header("Location: /register-success");
            exit();
        } else {
            exit("Registration failed.");
        }
    }
}
