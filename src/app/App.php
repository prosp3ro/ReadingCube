<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\RouteException;

class App
{
    public static Container $container;

    public function __construct(
        protected Router $router,
        protected array $request
    ) {
    }

    public function run(): void
    {
        try {
            $this->router->resolve(
                $this->request["uri"],
                $this->request["method"]
            );
        } catch (RouteException) {
            http_response_code(404);

            View::create('error/404', [
                "header" => "Not Found | " . APP_NAME
            ])->render();
        }

    }
}
