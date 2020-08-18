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
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'lquintana');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'lquintana');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

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
        $this->app->singleton('googlepopulartime', function ($app) {
            return new GooglePopularTime;
        });
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
        ], 'googlepopulartime.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/lquintana'),
        ], 'googlepopulartime.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/lquintana'),
        ], 'googlepopulartime.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/lquintana'),
        ], 'googlepopulartime.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
