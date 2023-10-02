<?php

declare(strict_types=1);

namespace Src\Controllers;

use PDOException;
use Src\Exceptions\DatabaseQueryException;
use Src\Models\DB;
use Src\View;

class IndexController
{
    private View $view;
    private DB $db;
    private ?int $sessionUserId = null;
    private ?array $user;

    public function __construct(View $view, DB $db)
    {
        $this->view = $view;
        $this->db = $db;

        if (isset($_SESSION['user_id'])) {
            $this->sessionUserId = $_SESSION["user_id"];

            $sql = "SELECT * FROM users WHERE id = ?";
            $statement = $this->db->prepare($sql);

            try {
                $statement->execute([$this->sessionUserId]);
                $this->user = $statement->fetch();
            } catch (PDOException $exception) {
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

        // TODO its own method
        $sql = "SELECT * FROM books";
        $statement = $this->db->prepare($sql);

        try {
            $statement->execute();
            $results = $statement->fetchAll();

            return $this->view->render("index", [
                "books" => $results,
                "user" => $this->user ?? null
            ]);
        } catch (PDOException $exception) {
            throw new DatabaseQueryException($exception->getMessage());
        }
    }

    public function showAboutUsPage()
    {
        return $this->view->render("about-us", [
            "header" => "About Us | " . APP_NAME,
            "user" => $this->user
        ]);
    }

    public function showContactPage()
    {
        return $this->view->render("contact", [
            "header" => "Contact | " . APP_NAME,
            "user" => $this->user
        ]);
    }

    public function showFAQPage()
    {
        return $this->view->render("faq", [
            "header" => "FAQ | " . APP_NAME,
            "user" => $this->user
        ]);
    }

    public function pageNotFound()
    {
        return $this->view->pageNotFound([
            "user" => $this->user ?? null,
        ]);
    }
}
