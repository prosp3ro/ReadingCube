<?php

declare(strict_types=1);

namespace Src\Controllers;

use Src\Exceptions\DatabaseQueryException;
use Src\Models\DB;
use Src\View;
use Throwable;

class UserController
{
    private View $view;
    private DB $db;

    public function __construct(View $view, DB $db)
    {
        $this->view = $view;
        $this->db = $db;
    }

    public function showEditProfilePage()
    {
        if (isset($_SESSION['user_id'])) {
            $sessionUserId = $_SESSION["user_id"];
            $sql = "SELECT * FROM users WHERE id = ?";
            $statement = $this->db->prepare($sql);

            try {
                $statement->execute([$sessionUserId]);
                $userData = $statement->fetch();

                return $this->view->render("edit-profile", [
                    "userData" => $userData
                ]);
            } catch (Throwable $exception) {
                throw new DatabaseQueryException($exception->getMessage());
                exit();
            }
        }

        header("Location: /login");
        exit();
    }

    public function editProfileData()
    {
        
    }
}
