<?php

declare(strict_types=1);

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model as EloquentBaseModel;

class User extends EloquentBaseModel
{
    protected $table = 'books';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'book_name', 'book_author', 'book_year'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];
}
