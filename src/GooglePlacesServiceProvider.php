<?php

namespace lquintana\GooglePlaces;

use Illuminate\Support\ServiceProvider;

class GooglePlacesServiceProvider extends ServiceProvider
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
        $this->mergeConfigFrom(__DIR__.'/../config/googleplaces.php', 'googleplaces');

        // Register the service the package provides.
        // $this->app->singleton('googleplaces', function ($app) {
        //     return new GooglePlaces("desde service provider");
        // });

        $this->app->singleton('googleplaces', function ($app) {
            $key = isset($app['config']['google.places.key'])
                ? $app['config']['googleplaces.places.key'] : "llega por service provider";
            return new GooglePlaces($key);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['googleplaces'];
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
            __DIR__.'/../config/googleplaces.php' => config_path('googleplaces.php'),
        ], 'googleplaces.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/lquintana'),
        ], 'googleplaces.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/lquintana'),
        ], 'googleplaces.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/lquintana'),
        ], 'googleplaces.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
