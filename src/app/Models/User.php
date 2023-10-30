<?php

declare(strict_types=1);

namespace App\Models;

use App\Exceptions\DatabaseQueryException;
use Illuminate\Database\Capsule\Manager as QueryBuilder;

class User
{
    public function getCurrentUser(?int $userId): object|null
    {
        if (is_null($userId)) {
            return null;
        }

        try {
            return QueryBuilder::table("users")
                ->where("id", "=", $userId)
                ->first();
        } catch (\Throwable $exception) {
            throw new DatabaseQueryException($exception->getMessage());
        }
    }

    public function verifyPassword(int $userId = null, string $password): bool
    {
        try {
            $user = QueryBuilder::table("users")
                ->select("password", "id")
                ->where("id", "=", $userId)
                ->first();
        } catch (\Throwable $exception) {
            throw new DatabaseQueryException($exception->getMessage());
        }

        $passwordVerified = password_verify($password, $user->password);

        return $user && $passwordVerified;
        
    }

    public function updateProfile(int $userId = null, array $dataToUpdate)
    {
        QueryBuilder::beginTransaction();

        try {
            QueryBuilder::table("users")
                ->where("id", "=", $userId)
                ->update($dataToUpdate);

            QueryBuilder::commit();
        } catch (\Throwable $exception) {
            // if (QueryBuilder::inTransaction()) {
            QueryBuilder::rollback();
            // }

            throw new DatabaseQueryException('Registration failed: ' . $exception->getMessage());
        }
    }

    public function updatePassword(int $userId = null, string $newPassword)
    {
        QueryBuilder::beginTransaction();

        try {
            QueryBuilder::table("users")
                ->where("id", "=", $userId)
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
