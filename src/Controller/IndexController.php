<?php

declare(strict_types=1);

namespace Src\Controller;

use Src\Exception\DatabaseQueryException;
use Src\Model\DB;
use Src\View;
use Throwable;

class IndexController
{
    private View $view;
    private DB $db;

    public function __construct()
    {
        $this->view = new View();
        $this->db = new DB();
    }

    public function index()
    {
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
    }
}
