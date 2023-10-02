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
        dd($id);
        die();
    }
}
