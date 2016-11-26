<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\CHD\Request;

class RequestServiceProvider extends ServiceProvider
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
        $this->app->singleton('Request', function () {
            return new Request();
        });
    }
}
