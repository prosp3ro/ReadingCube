<?php

declare(strict_types = 1);

namespace Src\Controller;

use App\View;

class IndexController
{
    public function index()
    {
        $view = new View();

        return $view->render("index");
    }
}
