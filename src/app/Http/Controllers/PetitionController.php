<?php

namespace App\Http\Controllers;

use App\Petition;
use App\SignatureStats;

class PetitionController extends Controller
{
    public function index($page = 1)
    {
        $offset = ((int) $page - 1) * env('ITEMS_PER_PAGE');

        $petitions = Petition::includingStatus(env('ITEMS_PER_PAGE'), $offset);

        $count = Petition::count();

        $weeklyStats = SignatureStats::where('scope', 'global')
            ->where('unit', 'hour')
            ->orderBy('delta', 'desc')
            ->get();

        return view('petitions', compact('petitions', 'weeklyStats', 'count', 'page'));
    }

    public function show($petition)
    {
        $petition = Petition::where('number', $petition)->first();

        return view('petition', compact('', 'petition'));
    }
}
