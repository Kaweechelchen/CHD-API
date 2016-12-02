<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\CHD\Stats;

class StatsServiceProvider extends ServiceProvider
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
        $this->app->singleton('Stats', function () {
            return new Stats();
        });
    }
}
