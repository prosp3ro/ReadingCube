<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Exceptions\RouteException;
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

        $this->assertSame($expected, $this->router->getRoutes());
    }

    /** @test */
    public function there_are_no_routes_when_router_is_created(): void
    {
        $this->assertEmpty((new Router)->getRoutes());
    }

    /**
     * @test
     * @dataProvider routeNotFoundCases
     */
    public function throws_route_not_found_exception(string $requestUri, string $requestMethod): void
    {
        $users = new class() {
            public function delete(): bool
            {
                return true;
            }
        };

        $this->router->get('/index', ['UserController', 'index']);
        $this->router->post('/users', [$users::class, 'store']);

        $this->expectException(RouteException::class);
        $this->router->resolve($requestUri, $requestMethod);
    }

    public static function routeNotFoundCases(): array
    {
        return [
            ['/users', 'put'],
            ['/invoices', 'post'],
            ['/users', 'get'],
            ['/users', 'post'],
        ];
    }
}
