<?php

namespace Amir2b\Instagram;

use Illuminate\Support\ServiceProvider;

class InstagramServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        ## config
        $this->mergeConfigFrom(__DIR__.'/../config/instagram.php', 'instagram');

        ## publishes
        $this->publishes([
            __DIR__.'/../config/' => config_path(''),
        ], 'config');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
