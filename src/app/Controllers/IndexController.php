<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Book;
use App\Models\User;
use App\View;

class IndexController
{
    private ?object $user = null;

    public function __construct()
    {
        $userId = $_SESSION["user_id"] ?? null;

        if (isset($userId)) {
            $this->user = (new User())->getCurrentUser((int) $userId ?? null);
        }
    }

    public function index()
    {
        // if (! $this->user) {
        //     header("Location: /login");
        //     exit();
        // }

        $books = (new Book)->getBooks();

        // dd($this->user);

        // dd($_SESSION);

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
