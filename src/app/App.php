<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\RouteException;
use App\Helpers\Captcha;
use Illuminate\Container\Container;

class App
{
    public function __construct(
        protected Router $router,
        protected array $request
    ) {
    }

    public function run()
    {
        $container = new Container();

        $container->bind(Captcha::class, function () {
            return new Captcha(GOOGLE_RECAPTCHA_SITE_KEY, GOOGLE_RECAPTCHA_SECRET_KEY);
        });

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

    public function setContainer()
    {
        
    }
}
