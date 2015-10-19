<?php

namespace Gocanto\SimpleAdmin;

use Illuminate\Support\ServiceProvider;

class SimpleAdminServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (is_dir(base_path() . '/resources/views/gocanto/simpleAdmin')) {
            $this->loadViewsFrom(base_path() . '/resources/views/gocanto/simpleAdmin', 'simpleAdmin');
        } else {
            $this->loadViewsFrom(__DIR__.'/views', 'simpleAdmin');
        }

        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/gocanto/simpleAdmin'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__.'/routes.php';
    }
}
