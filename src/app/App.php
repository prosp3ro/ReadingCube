<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\RouteException;
use App\Helpers\Captcha;

class App
{
    public static $container;

    public function __construct(
        protected Router $router,
        protected array $request
    ) {
    }

    public function run(): void
    {
        $container = new Container();

        $container->bind(Captcha::class, function () {
            return new Captcha(GOOGLE_RECAPTCHA_SITE_KEY, GOOGLE_RECAPTCHA_SECRET_KEY);
        });

        static::$container = $container;

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

    public static function container()
    {
        return static::$container;
    }

    public static function resolve($key)
    {
        return static::container()->resolve($key);
    }
}
