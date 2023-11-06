<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Container;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    /**
     * @test 
     */
    public function allows_to_register_services_using_closures(): void
    {
        $container = new Container();

        $container->bind('Service', fn() => new TestService());

        $this->assertTrue($container->has('Service'));
        $this->assertInstanceOf(TestService::class, $container->resolve('Service'));
        $this->assertNotSame($container->resolve('Service'), $container->resolve('Service'));
    }
}

class TestService
{
}
