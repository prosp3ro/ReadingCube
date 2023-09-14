<?php

declare(strict_types=1);

namespace Src\Controller;

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
            if (APP_ENVIRONMENT === "production") {
                echo "<h1>An error occurred. Please try again later.</h1>";
            } else if (APP_ENVIRONMENT === "development") {
                showException($exception);
            }
        }
    }
}
