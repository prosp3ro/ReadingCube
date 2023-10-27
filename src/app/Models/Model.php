<?php

declare(strict_types=1);

namespace App\Models;

abstract class Model
{
    protected DB $db;

    public function __construct()
    {
        $this->db = new DB();
    }
}
