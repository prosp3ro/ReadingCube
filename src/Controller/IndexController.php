<?php

declare(strict_types = 1);

namespace Src\Controller;

use Src\View;

class IndexController
{
    private View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function index()
    {
        return $this->view->render("index");
    }
}
