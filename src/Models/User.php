<?php

declare(strict_types=1);

namespace Src\Models;

class User
{
    private string $username;
    private string $email;
    private string $password;
    private int $isAdmin;

    public function __construct(string $username, string $email, string $password, int $isAdmin = 0)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->isAdmin = $isAdmin;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    // public function isAdmin()
    // {

    // }
}
