<?php

declare(strict_types=1);

namespace Src\Controllers;

use Illuminate\Database\Capsule\Manager as DB;
use Src\Exceptions\DatabaseQueryException;
use Src\View;
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

    // public function showAboutUsPage()
    // {
    //     return $this->view->render("about-us", [
    //         "header" => "About Us | " . APP_NAME,
    //         "user" => $this->user
    //     ]);
    // }

    // public function showContactPage()
    // {
    //     return $this->view->render("contact", [
    //         "header" => "Contact | " . APP_NAME,
    //         "user" => $this->user
    //     ]);
    // }

    // public function showFAQPage()
    // {
    //     return $this->view->render("faq", [
    //         "header" => "FAQ | " . APP_NAME,
    //         "user" => $this->user
    //     ]);
    // }

    // public function pageNotFound()
    // {
    //     return $this->view->pageNotFound([
    //         "user" => $this->user
    //     ]);
    // }
}
