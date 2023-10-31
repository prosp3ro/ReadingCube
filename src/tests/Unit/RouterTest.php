<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    private Router $router;

    protected function setUp(): void
    {
        $this->router = new Router();
    }

    /** @test */
    public function registers_a_route(): void
    {
        $this->router->register('get', '/users', ['UserController', "index"]);

        $expected = [
            'get' => [
                '/users' => ['UserController', 'index']
            ]
        ];

        $this->assertEquals($expected, $this->router->getRoutes());
    }

    /** @test */
    public function there_are_no_routes_when_router_is_created(): void
    {
        $this->assertEmpty((new Router)->getRoutes());
    }

    // public function throws_route_not_found_exception(): void
    // {
    //     $this->router->get('/index', ['IndexController', 'index']);
    //     $this->router->post('/users', ['UserController', 'store']);

    //     $this->
    // }
}
