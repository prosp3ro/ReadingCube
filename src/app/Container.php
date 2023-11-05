<?php

declare(strict_types=1);

namespace App;

class ClassName
{
    protected array $bindings = [];

    public function bind($key, callable $resolver)
    {
        $this->bindings[$key] = $resolver;
    }

    public function resolve($binding)
    {
        if (! array_key_exists($this->bindings, $binding)) {
            throw new \Exception("Binding not found.");
        }

        return new $this->bindings[$binding];
    }
}
