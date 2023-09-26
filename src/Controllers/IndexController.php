<?php

declare(strict_types=1);

namespace Src\Controllers;

use Src\Exceptions\DatabaseQueryException;
use Src\Models\DB;
use Src\View;
use Throwable;

class IndexController
{
    private View $view;
    private DB $db;
    private ?int $sessionUserId = null;

    public function __construct(View $view, DB $db)
    {
        $this->view = $view;
        $this->db = $db;
    }

    public function index()
    {
        $this->userLoginCheck();

        try {
            $sql = "SELECT * FROM books";
            $statement = $this->db->prepare($sql);
            $statement->execute();
            $results = $statement->fetchAll();

            return $this->view->render("index", [
                "books" => $results
            ]);
        } catch (Throwable $exception) {
            throw new DatabaseQueryException($exception->getMessage(), 0, $exception);
        }

        header("Location: /login");
        exit();
    }

    public function showAboutUsPage()
    {
        return $this->view->render("about-us", [
            "header" => "About Us | " . APP_NAME,
        ]);
    }

    public function showContactPage()
    {
        return $this->view->render("contact", [
            "header" => "Contact | " . APP_NAME,
        ]);
    }

    public function showFAQPage()
    {
        return $this->view->render("faq", [
            "header" => "FAQ | " . APP_NAME,
        ]);
    }

    // check if user is logged in - middleware?
    private function userLoginCheck()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }

        $this->sessionUserId = $_SESSION["user_id"];

        // new User object...
    }
}
