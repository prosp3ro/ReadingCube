<?php

declare(strict_types=1);

namespace Src\Model;

use Src\Exception\DatabaseQueryException;

class UserRepository
{
    private DB $db;

    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    public function createUser(User $user): bool
    {
        $sql = "INSERT INTO users(username, email, password) VALUES (?, ?, ?)";

        try {
            $statement = $this->db->prepare($sql);
            $hashedPassword = password_hash($user->getPassword(), PASSWORD_BCRYPT, [
                "cost" => 12
            ]);

            return $statement->execute([$user->getUsername(), $user->getEmail(), $hashedPassword]);
        } catch (\Throwable $exception) {
            throw new DatabaseQueryException($exception->getMessage());
        }
    }

    public function isEmailUnique(string $email): bool
    {
        $sql = "SELECT COUNT(*) FROM users WHERE email = ?";

        try {
            $statement = $this->db->prepare($sql);
            $statement->execute([$email]);
            return (int)$statement->fetchColumn() > 0;
        } catch (\Throwable $exception) {
            throw new DatabaseQueryException($exception->getMessage());
        }
    }

    public function isUsernameUnique(string $username): bool
    {
        $sql = "SELECT COUNT(*) FROM users WHERE username = ?";

        try {
            $statement = $this->db->prepare($sql);
            $statement->execute([$username]);
            return (int)$statement->fetchColumn() > 0;
        } catch (\Throwable $exception) {
            throw new DatabaseQueryException($exception->getMessage());
        }
    }
}
