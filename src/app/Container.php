<?php

declare(strict_types=1);

namespace App;

use Closure;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionUnionType;

class Container
{
    public array $services = [];

    // accept callable instead of classes so there is only one instance of registered service
    public function bind(string $key, callable $value): self
    {
        $this->services[$key] = $value;

        return $this;
    }

    public function get($id)
    {
        if ($this->has($id)) {
            $service = $this->services[$id];

            return $service($this);
        }

        return $this->resolve($id);
    }

    public function has($id): bool
    {
        return array_key_exists($id, $this->services);
    }

    public function resolve($id)
    {
        $reflection = new ReflectionClass($id);

        if (! $reflection->isInstantiable()) {
            throw new \Exception("Class {$id} is not instantiable.");
        }

        $con = $reflection->getConstructor();

        if (! $con) {
            return new $id;
        }

        $parameters = $con->getParameters();

        if (! $parameters) {
            return new $id;
        }

        $dependencies = array_map(function (ReflectionParameter $param) use ($id) {
            $name = $param->getName();
            $type = $param->getType();

            if (! $type) {
                throw new \Exception("Failed to resolve class {$id} because param {$name} is missing a type hint.");
            }

            if ($type instanceof ReflectionUnionType) {
                throw new \Exception("Failed to resolve class {$id} because of union type for param {$name}.");
            }

            if ($type instanceof ReflectionNamedType && ! $type->isBuiltin()) {
                return $this->get($type->getName());
            }

            throw new \Exception("Failed to resolve {$id} because of invalid param {$name}.");
        }, $parameters);

        return $reflection->newInstanceArgs($dependencies);
    }
}
