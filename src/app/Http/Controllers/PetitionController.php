<?php

namespace App\Http\Controllers;

use App\Petition;
use App\Status;
use App\Signature;

class PetitionController extends Controller
{
    public function index()
    {
        //return app('Stats')->hours();

        //return app('Stats')->days();

        $petitions = Petition::withStatus();

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

        return view('petitions', compact('petitions'));
    }

    //public function show(Petition $petition)
    public function show($petition)
    {
        $status    = Status::all();

        $petition = Petition::where('number', $petition)->first();

        return view('petition', compact('petition', 'status'));
    }
}
