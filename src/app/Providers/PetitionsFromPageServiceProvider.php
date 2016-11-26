<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\CHD\PetitionsFromPage;

class PetitionsFromPageServiceProvider extends ServiceProvider
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
        $this->app->singleton('PetitionsFromPage', function () {
            return new PetitionsFromPage();
        });
    }
}
