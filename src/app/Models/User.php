<?php

declare(strict_types=1);

namespace App\Models;

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

    public function update()
    {
        
    }
}
