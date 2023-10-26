<?php

declare(strict_types=1);

namespace App\Controllers;

use Illuminate\Database\Capsule\Manager as DB;
use App\Exceptions\DatabaseQueryException;
use App\View;

class ItemController
{
    private ?int $sessionUserId = null;
    private ?object $user = null;

    public function __construct()
    {
        if (isset($_SESSION['user_id'])) {
            $this->sessionUserId = (int) $_SESSION["user_id"];

            try {
                $this->user = DB::table("users")
                    ->where("id", "=", $this->sessionUserId)
                    ->first()
                ;
            } catch (\Throwable $exception) {
                throw new DatabaseQueryException($exception->getMessage());
            }
        }
    }

    public function index(int $id)
    {
        try {
            $item = DB::table("books")
                ->where("id", "=", $id)
                ->first();
        } catch (\Throwable $exception) {
        }

        if (!$item) {
            return View::create("error/404", [
                "user" => $this->user
            ])->render();
        }

        return View::create("item", [
            "header" => "Item | " . APP_NAME,
            "item" => $item,
            "user" => $this->user
        ])->render();
    }
}