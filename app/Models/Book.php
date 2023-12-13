<?php

declare(strict_types=1);

namespace App\Models;

use App\Exceptions\DatabaseQueryException;
use Illuminate\Database\Capsule\Manager as QueryBuilder;

class Book
{
    public function getBooks(): object|null
    {
        try {
            return QueryBuilder::table("books")->get();
        } catch (\Throwable $exception) {
            throw new DatabaseQueryException($exception->getMessage());
        }
    }

    public function getBook(int $bookId): object|null
    {
        try {
            return QueryBuilder::table("books")
                ->where("id", "=", $bookId)
                ->first();
        } catch (\Throwable $exception) {
            throw new DatabaseQueryException($exception->getMessage());
        }
    }
}
