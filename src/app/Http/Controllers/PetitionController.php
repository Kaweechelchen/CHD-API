<?php

namespace App\Http\Controllers;

use App\Petition;
use App\Signature;
use App\SignatureStats;

class PetitionController extends Controller
{
    public function index($page = 1)
    {
        $offset         = ((int) $page - 1) * env('ITEMS_PER_PAGE');
        $count          = Petition::count();
        $petitions      = Petition::includingStatus(env('ITEMS_PER_PAGE'), $offset);
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

        return view(
            'petitions',
            compact(
                'petitions',
                'weeklyStats',
                'count',
                'page',
                'stats'
            )
        );
    }

    public function show(Petition $petition)
    {
        $petition = Petition::where('number', $petition)->first();

        return view('petition', compact('', 'petition'));
    }
}
