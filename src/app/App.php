<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\RouteException;
use App\Helpers\Captcha;
use Illuminate\Database\Capsule\Manager;

class App
{
    private Config $config;

    public function __construct(
        protected Container $container,
        protected ?Router $router = null,
        protected array $request = [],
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

    public function boot(): static
    {
        $this->config = new Config($_ENV);
        $this->initEloquent($this->config->eloquent);

        $this->container->bind(Captcha::class, fn() => new Captcha(GOOGLE_RECAPTCHA_SITE_KEY, GOOGLE_RECAPTCHA_SECRET_KEY));

        return $this;
    }

    public function initEloquent(array $config)
    {
        $capsule = new Manager();

        $capsule->addConnection($config);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}
