<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Book;
use App\Models\User;
use App\View;

class ItemController
{
    private ?object $user = null;

    public function __construct()
    {
        $userId = $_SESSION["user_id"] ?? null;

        if (isset($userId)) {
            $this->user = (new User())->getCurrentUser((int) $userId ?? null);
        }
    }

    public function index(int $id)
    {
        $item = (new Book)->getBook($id);

        if (! $item) {
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
