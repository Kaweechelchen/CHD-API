<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\CHD\PetitionPages;

class PetitionPagesServiceProvider extends ServiceProvider
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
        $this->app->singleton('PetitionPages', function () {
            return new PetitionPages();
        });
    }
}
