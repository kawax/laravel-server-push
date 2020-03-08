<?php

namespace Revolution\ServerPush\Providers;

use Illuminate\Support\ServiceProvider;
use Revolution\ServerPush\LinkBuilder;

class ServerPushServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/server-push.php' => config_path('server-push.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/server-push.php', 'server-push'
        );

        $this->app->singleton(LinkBuilder::class, function ($app) {
            return new LinkBuilder();
        });
    }
}
