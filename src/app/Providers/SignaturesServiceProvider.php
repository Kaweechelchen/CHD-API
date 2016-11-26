<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\CHD\Signatures;

class SignaturesServiceProvider extends ServiceProvider
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
        $this->app->singleton('Signatures', function () {
            return new Signatures();
        });
    }
}
