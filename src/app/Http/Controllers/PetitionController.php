<?php

namespace App\Http\Controllers;

use App\Petition;
use App\Status;
use App\Signature;
use App\SignatureStats;

class PetitionController extends Controller
{
    public function index()
    {
        //app('Stats')->init();

        //return app('Stats')->hours();

        //return app('Stats')->days();

        //return app('Stats')->petitionSignaturesByDay(807, 4);

        $petitions = Petition::includingStatus(20);
        $petitions = Petition::withStatus(4, 200);

        //$petitions = Petition::all();
        /*$petitions = Petition::orderBy('number', 'desc')
            ->limit(20)
            ->get();*/

        /*$count = Signature::whereBetween(
            'created_at', [
                date('Y-m-d H:i:s', strtotime('-4 days')),
                date('Y-m-d H:i:s'),
            ]
        )->count();*/

        //dd($count);

        //dd();

        $weeklyStats = SignatureStats::where('scope', 'global')
            ->where('unit', 'hour')
            ->orderBy('delta', 'desc')
            ->get();

        return view('petitions', compact('petitions', 'weeklyStats'));
    }

    //public function show(Petition $petition)
    public function show($petition)
    {
        $status    = Status::all();

        $petition = Petition::where('number', $petition)->first();

        return view('petition', compact('petition', 'status'));
    }
}
