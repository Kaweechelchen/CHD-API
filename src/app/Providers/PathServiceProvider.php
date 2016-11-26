<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\CHD\Path;

class PathServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->singleton('Path', function () {
            return new Path();
        });
    }
}
