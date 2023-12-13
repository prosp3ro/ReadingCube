<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Capsule\Manager as DB;

class Validator
{
    // max 20 characters
    private string $usernameRegex = "/^[a-zA-Z0-9]{5,}$/";
    // max 100 characters
    // these extra characters can be used too: `~!@#$%^&*()-_=+[{]};:'",<.>/?\|
    private string $passwordRegex = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/m";

    public function validate(array $args)
    {
        $username = $args["username"] ?? "";
        $email = $args["email"] ?? "";
        $password = $args["password"] ?? "";
        $passwordConfirmation = $args["password_confirmation"] ?? "";

        if (! empty($username)) {
            if (! preg_match($this->usernameRegex, $username)) {
                exit("Invalid username format. Please use only letters and numbers, and ensure it's at least 5 characters long.");
            }

            if (! $this->isUsernameUnique($username)) {
                exit("Username is already taken.");
            }
        }

        if (! empty($email)) {
            if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                exit("Email has invalid format.");
            }

            if (! $this->isEmailUnique($email)) {
                exit("Email is already taken.");
            }
        }

        if (! empty($password)) {
            if (empty($passwordConfirmation)) {
                throw new \Exception("password_confirmation key does not exist in validate arguments.");
            }

            if (! preg_match($this->passwordRegex, $password)) {
                exit("Password must be at least 8 characters and contain at least 1 uppercase letter, 1 lowercase letter, and 1 number.");
            }

            if ($password !== $passwordConfirmation) {
                exit("Passwords must match.");
            }
        }
    }

    public function isEmailAvailableJson(string $email)
    {
        if (! empty($email)) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo $this->isUnique("email", $email);
                exit();
            } else {
                header("Content-Type: application/json");

                echo json_encode(
                    [
                        "error" => "Email has invalid format."
                    ]
                );

                exit();
            }
        }
    }

    private function isEmailUnique(string $email)
    {
        $json = $this->isUnique("email", $email);
        $jsonObject = json_decode($json);

        return $jsonObject->available === true;
    }

    private function isUsernameUnique(string $username)
    {
        $json = $this->isUnique("username", $username);
        $jsonObject = json_decode($json);

        return $jsonObject->available === true;
    }

    private function isUnique(string $type, string $data)
    {
        $isAvailable = DB::table("users")
            ->where($type, "=", $data)
            ->count();

        header("Content-Type: application/json");

        $jsonData = json_encode(
            [
                "available" => (bool) !$isAvailable
            ]
        );

        return $jsonData;
    }
}
