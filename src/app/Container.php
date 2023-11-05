<?php

declare(strict_types=1);

namespace App;

class Container
{
    public array $bindings = [];

    public function bind($key, callable $resolver)
    {
        $this->bindings[$key] = $resolver;
    }

    public function resolve($key)
    {
        if (! array_key_exists($key, $this->bindings)) {
            throw new \Exception("Binding not found.");
        }

        $resolver = $this->bindings[$key];

        return call_user_func($resolver);
    }
}
