<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Container;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    /**
     * @var Container
     */
    protected $container;
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->container = new Container();
    }

    /**
     * @test 
     */
    public function allows_to_register_services_using_closures(): void
    {
        $this->container = new Container();

        $this->container->bind('Service', fn() => new TestService());

        $this->assertTrue($this->container->has('Service'));
        $this->assertInstanceOf(TestService::class, $this->container->resolve('Service'));
        // it should return single instance
        $this->assertNotSame($this->container->resolve('Service'), $this->container->resolve('Service'));
    }
}

class TestService
{
}
