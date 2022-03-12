<?php

namespace Revolution\ServerPush\Providers;

use Illuminate\Support\ServiceProvider;

class ServerPushServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/server-push.php',
            'server-push'
        );
    }

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes(
            [
                __DIR__.'/../../config/server-push.php' => config_path('server-push.php'),
            ],
            'server-push-config'
        );
    }
}
