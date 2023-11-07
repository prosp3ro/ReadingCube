<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Container;
use PHPUnit\Framework\TestCase;
use PHPUnit\Util\Test;
use ReflectionClass;

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
    public function it_allows_to_register_services_using_closures(): void
    {
        $this->container = new Container();

        $this->container->bind('Service', fn() => new TestService());

        $this->assertTrue($this->container->has('Service'));
        $this->assertInstanceOf(TestService::class, $this->container->resolve('Service'));
        // it should return single instance
        $this->assertNotSame($this->container->resolve('Service'), $this->container->resolve('Service'));
    }

    /**
     * @test 
     */
    // public function it_resolves_services_inside_new_binding_with_anonymous_function()
    // {
    //     $this->container->bind(DependencyClassOne::class, fn () => new DependencyClassOne);
    //     $this->container->bind(DependencyClassTwo::class, fn () => new DependencyClassTwo);
    //     $this->container->bind(TestService::class, fn (Container $c) => new TestService($c->resolve(DependencyClassOne::class, DependencyClassTwo::class)));

    //     $resolvedService = $this->container->resolve(TestService::class);

    //     $this->assertInstanceOf(TestService::class, $resolvedService);

    //     $reflection = new ReflectionClass($resolvedService);
    //     $constructor = $reflection->getConstructor();
    //     $parameters = $constructor->getParameters();

    //     $this->assertCount(2, $parameters);
    //     $this->assertEquals(DependencyClassOne::class, $parameters[0]->getType()->getName());
    //     $this->assertEquals(DependencyClassTwo::class, $parameters[1]->getType()->getName());
    // }

}

class TestService
{
}

class DependencyClassOne
{
    
}

class DependencyClassTwo
{
    
}
