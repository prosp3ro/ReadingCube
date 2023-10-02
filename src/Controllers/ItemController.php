<?php

declare(strict_types=1);

namespace Src\Controllers;

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
    }

    public function index(int $id)
    {
        $sql = "SELECT * FROM books where id = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id]);
        $results = $statement->fetch();

        dd($results);
        die();

        return $this->view->render("item", [
            "header" => "Item | " . APP_NAME,
            "id" => $id
        ]);
    }
}
