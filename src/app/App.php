<?php

declare(strict_types=1);

namespace App;

use App\Controllers\Auth\LoginController;
use App\Controllers\Auth\RegisterController;
use App\Controllers\IndexController;
use App\Controllers\ResetPasswordController;
use App\Controllers\UserController;
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
        $this->addRoutes();

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

    // TODO keep routes in routes/web.php
    public function addRoutes(): void
    {
        $this->router
            ->get("/", [IndexController::class, "index"])
            ->get("/about-us", [IndexController::class, "showAboutUsPage"])
            ->get("/contact", [IndexController::class, "showContactPage"])
            ->get("/faq", [IndexController::class, "showFAQPage"])
            ->post("/upload", [IndexController::class, "upload"])

            ->get("/register", [RegisterController::class, "index"])
            ->post("/register", [RegisterController::class, "register"])

            ->get("/login", [LoginController::class, "index"])
            ->post("/login", [LoginController::class, "login"])
            ->get("/logout", [LoginController::class, "logout"])

            // TODO method should be `index` and ProfileController
            ->get("/edit-profile", [UserController::class, "showEditProfilePage"])
            // updateProfileData
            ->post("/edit-profile", [UserController::class, "updateProfile"])
            ->post("/update-password", [UserController::class, "updatePassword"])
            ->get("/forgot-password", [ResetPasswordController::class, "index"])
            ->post("/forgot-password", [ResetPasswordController::class, "resetPassword"]);
    }
}
