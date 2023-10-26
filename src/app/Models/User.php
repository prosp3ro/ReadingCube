<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'username', 'email', 'password'
    ];

    protected $hidden = [
        'password'
    ];

    public $timestamps = true;
}