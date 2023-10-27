<?php

declare(strict_types=1);

namespace App\Models;

use App\Exceptions\DatabaseQueryException;
use Illuminate\Database\Capsule\Manager as QueryBuilder;

class User
{
    public function __construct()
    {
        
    }

    public function getCurrentUser(int $sessionUserId = null): object|null
    {
        try {
            $user = QueryBuilder::table("users")
                ->where("id", "=", $sessionUserId)
                ->first();

            return $user;
        } catch (\Throwable $exception) {
            throw new DatabaseQueryException($exception->getMessage());
        }

        return null;
    }

    public function verifyPassword(int $sessionUserId = null, string $password): bool
    {
        try {
            $user = QueryBuilder::table("users")
                ->select("password", "id")
                ->where("id", "=", $sessionUserId)
                ->first();
        } catch (\Throwable $exception) {
            throw new DatabaseQueryException($exception->getMessage());
        }

        $passwordVerified = password_verify($password, $user->password);

        return $user && $passwordVerified;
        
    }

    public function updateProfile(int $sessionUserId = null, array $dataToUpdate)
    {
        QueryBuilder::beginTransaction();

        try {
            QueryBuilder::table("users")
                ->where("id", "=", $sessionUserId)
                ->update($dataToUpdate);

            QueryBuilder::commit();
        } catch (\Throwable $exception) {
            // if (QueryBuilder::inTransaction()) {
            QueryBuilder::rollback();
            // }

            throw new DatabaseQueryException('Registration failed: ' . $exception->getMessage());
        }
    }

    public function updatePassword(int $sessionUserId = null, string $newPassword)
    {
        QueryBuilder::beginTransaction();

        try {
            QueryBuilder::table("users")
                ->where("id", "=", $sessionUserId)
                ->update([
                    "password" => $newPassword
                ]);

            QueryBuilder::commit();
        } catch (\Throwable $exception) {
            // if (QueryBuilder::inTransaction()) {
            QueryBuilder::rollback();
            // }

            throw new DatabaseQueryException('Registration failed: ' . $exception->getMessage());
        }
    }
}
