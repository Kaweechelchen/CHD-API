<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Signature;
use App\SignatureStats;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $stats['day']   = Signature::where('created_at', '>', date('Y-m-d H:i:s', strtotime('-1 day')))
            ->where('created_at', '>', env('FIRST_SCRAPE_END'))
            ->count();
        $stats['week']  = Signature::where('created_at', '>', date('Y-m-d H:i:s', strtotime('-1 week')))
            ->where('created_at', '>', env('FIRST_SCRAPE_END'))
            ->count();
        $stats['month'] = Signature::where('created_at', '>', date('Y-m-d H:i:s', strtotime('-1 month')))
            ->where('created_at', '>', env('FIRST_SCRAPE_END'))
            ->count();
        $stats['total'] = Signature::count();

        $weeklyStats = SignatureStats::where('scope', 'global')
            ->where('unit', 'hour')
            ->orderBy('delta', 'desc')
            ->get();

        View::share(compact('stats'));
        View::share(compact('weeklyStats'));
    }

    /**
     * Register any application services.
     */
    public function register()
    {
    }
}
