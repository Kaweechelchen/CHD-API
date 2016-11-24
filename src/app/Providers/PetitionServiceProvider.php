<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\CHD\Petition;

class PetitionServiceProvider extends ServiceProvider
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
        $this->app->singleton('Petition', function () {
            return new Petition();
        });
    }
}
