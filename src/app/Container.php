<?php

declare(strict_types=1);

namespace App;

use Closure;

class Container
{
    public array $services = [];

    // accept callable instead of classes so there is only one instance of registered service
    public function bind(string $key, callable $value): self
    {
        $this->services[$key] = $value;

        return $this;
    }

    public function resolve($id)
    {
        if (! $this->has($id)) {
            throw new \Exception("Binding not found.");
        }

        $service = $this->services[$id];

        if ($service instanceof Closure) {
            return call_user_func($service);
        }

        return $service;
    }

    public function has($id): bool
    {
        return array_key_exists($id, $this->services);
    }
}
