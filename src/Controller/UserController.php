<?php

declare(strict_types=1);

namespace Src\Controller;

use Src\Model\DB;
use Src\View;

class UserController
{
    private View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function showProfile()
    {
        if (isset($_SESSION['user_id'])) {
            return $this->view->render("user-profile");
        }

        header("Location: /login");
        exit();
    }
}
