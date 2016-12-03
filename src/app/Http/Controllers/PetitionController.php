<?php

namespace App\Http\Controllers;

use App\Petition;
use App\Status;
use App\SignatureStats;

class PetitionController extends Controller
{
    public function index()
    {
        $petitions = Petition::includingStatus(20);
        $petitions = Petition::withStatus(4, 200);

        $weeklyStats = SignatureStats::where('scope', 'global')
            ->where('unit', 'hour')
            ->orderBy('delta', 'desc')
            ->get();

        return view('petitions', compact('petitions', 'weeklyStats'));
    }

    public function show($petition)
    {
        $status    = Status::all();

        $petition = Petition::where('number', $petition)->first();

        return view('petition', compact('petition', 'status'));
    }
}
