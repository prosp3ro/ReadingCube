<?php

declare(strict_types=1);

namespace App\Controllers;

use Illuminate\Database\Capsule\Manager as DB;
use App\Exceptions\DatabaseQueryException;
use App\View;
use Throwable;

class IndexController
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
            } catch (Throwable $exception) {
                throw new DatabaseQueryException($exception->getMessage());
            }
        }
    }

    public function index()
    {
        // if (!$this->sessionUserId) {
        //     header("Location: /login");
        //     exit();
        // }

        $books = DB::table("books")->get();

        return View::create("index", [
            "books" => $books,
            "user" => $this->user
        ])->render();
    }

    public function upload()
    {
        dd($_FILES);
    }

    public function showAboutUsPage()
    {
        return View::create("about-us", [
            "header" => "About Us | " . APP_NAME,
            "user" => $this->user
        ])->render();
    }

    public function showContactPage()
    {
        return View::create("contact", [
            "header" => "Contact | " . APP_NAME,
            "user" => $this->user
        ])->render();
    }

    public function showFAQPage()
    {
        return View::create("faq", [
            "header" => "FAQ | " . APP_NAME,
            "user" => $this->user
        ])->render();
    }

    public function pageNotFound()
    {
        return View::create("error/404", [
            "user" => $this->user
        ])->render();
    }
}
