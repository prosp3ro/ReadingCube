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

    public function getCurrentUser(int $sessionUserId): object
    {
        $user = QueryBuilder::table("users")
            ->where("id", "=", $sessionUserId)
            ->first();

        return $user;
    }

    public function updateProfile(int $sessionUserId, array $dataToUpdate)
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
}
