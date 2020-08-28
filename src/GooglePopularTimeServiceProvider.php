<?php

namespace LQuintana\GooglePopularTime;

use Illuminate\Support\ServiceProvider;

class GooglePopularTimeServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/googlepopulartime.php', 'googlepopulartime');

        // Register the service the package provides.
        $this->app->singleton(GooglePopularTime::class, function ($app) {
            return new GooglePopularTime($app['config']['googlepopulartime']);
        });

        $this->app->alias(GooglePopularTime::class, 'googlepopulartime');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['googlepopulartime'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/googlepopulartime.php' => config_path('googlepopulartime.php'),
        ], 'googlepopulartimes');
    }
}
