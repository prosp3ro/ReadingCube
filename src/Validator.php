<?php

declare(strict_types=1);

namespace Src;

use Illuminate\Database\Capsule\Manager as DB;

class Validator
{
    private string $usernameRegex = "/^[a-zA-Z0-9]{5,}$/";
    private string $passwordRegex = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/m";

    public function validate(array $args)
    {
        if (array_key_exists("username", $args)) {
            $username = $args["username"];

            if (!preg_match($this->usernameRegex, $username)) {
                exit("Invalid username format. Please use only letters and numbers, and ensure it's at least 5 characters long.");
            }

            $this->isUsernameUnique($username);
        }

        if (array_key_exists("email", $args)) {
            $email = $args["email"];

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                exit("Email has invalid format.");
            }

            $this->isEmailUnique($email);
        }

        if (array_key_exists("password", $args)) {
            if (!array_key_exists("password_confirmation", $args)) {
                throw new \Exception("password_confirmation key does not exist in validate arguments.");
            }

            $password = $args["password"];
            $passwordConfirmation = $args["password_confirmation"];

            if (!preg_match($this->passwordRegex, $password)) {
                exit("Password must be at least 8 characters and contain at least 1 uppercase letter, 1 lowercase letter, and 1 number.");
            }

            if ($password !== $passwordConfirmation) {
                exit("Passwords must match.");
            }
        }
    }

    public function isEmailAvailableJson(string $email)
    {
        if (!empty($email)) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo $this->isUnique("email", $email);
                exit();
            } else {
                header("Content-Type: application/json");

                echo json_encode([
                    "error" => "Email has invalid format."
                ]);

                exit();
            }
        }
    }

    private function isEmailUnique(string $email)
    {
        $json = $this->isUnique("email", $email);
        $jsonArray = json_decode($json, true);

        if ($jsonArray["available"] == false) {
            exit("Email is already taken.");
        }
    }

    private function isUsernameUnique(string $username)
    {
        $json = $this->isUnique("username", $username);
        $jsonArray = json_decode($json, true);

        if ($jsonArray["available"] == false) {
            exit("Username is already taken.");
        }
    }

    private function isUnique(string $type, string $data)
    {
        $isAvailable = DB::table("users")
            ->where($type, "=", $data)
            ->count();

        header("Content-Type: application/json");

        $jsonData = json_encode([
            "available" => (bool) !$isAvailable
        ]);

        return $jsonData;
    }
}
