<?php

namespace Codewiser\UAC\Laravel;

use Illuminate\Routing\Router;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/uac.php', 'uac'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/uac.php' => config_path('uac.php'),
        ]);
        $this->loadRoutesFrom(__DIR__.'/../routes/uac.php');

        $this->bootMiddleware();
    }

    private function bootMiddleware()
    {
        /** @var Router $router */
        $router = $this->app['router'];
        $router->aliasMiddleware('auth.oauth', AuthenticateWithOauth::class);
    }
}
