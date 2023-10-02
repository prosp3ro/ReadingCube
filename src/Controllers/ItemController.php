<?php

declare(strict_types=1);

namespace Src\Controllers;

use PDOException;
use Src\Exceptions\DatabaseQueryException;
use Src\Models\DB;
use Src\View;

class ItemController
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

    public function index(int $id)
    {
        $sql = "SELECT * FROM books where id = ?";
        $statement = $this->db->prepare($sql);

        try {
            $statement->execute([$id]);
            $item = $statement->fetch();
        } catch (\Throwable $exception) {
        }

        if (!$item) {
            $this->view->pageNotFound([
                "user" => $this->user ?? null
            ]);
            exit();
        }

        return $this->view->render("item", [
            "header" => "Item | " . APP_NAME,
            "item" => $item,
            "user" => $this->user ?? null
        ]);
    }
}
