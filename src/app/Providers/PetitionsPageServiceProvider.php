<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\CHD\PetitionsPage;

class PetitionsPageServiceProvider extends ServiceProvider
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
        $this->app->singleton('PetitionsPage', function () {
            return new PetitionsPage();
        });
    }
}
