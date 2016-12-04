<?php

namespace App\Http\Controllers;

use App\Petition;
use App\Signature;
use App\SignatureStats;

class PetitionController extends Controller
{
    public function index($page = 1)
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

        $offset         = ((int) $page - 1) * env('ITEMS_PER_PAGE');
        $count          = Petition::count();
        $petitions      = Petition::includingStatus(env('ITEMS_PER_PAGE'), $offset);

        return view(
            'petitions',
            compact(
                'petitions',
                'count',
                'page',
                'stats',
                'weeklyStats'
            )
        );
    }

    public function show($petition)
    {
        $petition       = Petition::where('number', $petition)->first();
        $stats['daily'] = SignatureStats::where('scope', 'petition')
            ->where('label', $petition->id)
            ->value('compiled');

        $stats['day']   = Signature::where('created_at', '>', date('Y-m-d H:i:s', strtotime('-1 day')))
            ->where('created_at', '>', env('FIRST_SCRAPE_END'))
            ->where('petition_id', $petition->id)
            ->count();
        $stats['week']  = Signature::where('created_at', '>', date('Y-m-d H:i:s', strtotime('-1 week')))
            ->where('created_at', '>', env('FIRST_SCRAPE_END'))
            ->where('petition_id', $petition->id)
            ->count();
        $stats['month'] = Signature::where('created_at', '>', date('Y-m-d H:i:s', strtotime('-1 month')))
            ->where('created_at', '>', env('FIRST_SCRAPE_END'))
            ->where('petition_id', $petition->id)
            ->count();

        $weeklyStats = SignatureStats::where('scope', 'global')
            ->where('unit', 'hour')
            ->orderBy('delta', 'desc')
            ->get();

        return view(
            'petition',
            compact(
                'petition',
                'stats'
            )
        );
    }
}
