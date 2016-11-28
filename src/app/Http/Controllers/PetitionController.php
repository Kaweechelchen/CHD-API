<?php

namespace App\Http\Controllers;

use App\Petition;
use App\Status;
use Illuminate\Support\Facades\DB;

class PetitionController extends Controller
{
    public function index()
    {
        //$petitions = Petition::all();
        $petitions = Petition::orderBy('submission_date', 'desc')
            ->limit(20)
            ->offset(19)
            ->get();

        /*DB::listen(function ($query) {
            dd($query->sql, $query->bindings, $query->time);
        });*/

        /*$petitions = Petition::join('petition_statuses', 'petitions.id', '=', 'petition_statuses.petition_id')
                             ->join('statuses',          'statuses.id',  '=', 'petition_statuses.status_id')
                             ->selectRaw('petitions.id, MAX(petition_statuses.created_at)')
                             //->latest('statuses.created_at')
                             //->havingRaw('MAX(petition_statuses.created_at) = petition_statuses.created_at')
                             //->latest('petition_statuses.created_at')
                             //->groupBy('petitions.id')
                             //->orderBy('petitions.created_at', 'desc')
                             //->where('petitions.id', 29)
                             ->limit(3)
                             //->offset(16)
                             ->get();*/

        //return $petitions;

        $status    = Status::all();

        return view('petitions', compact('petitions', 'status'));
    }

    //public function show(Petition $petition)
    public function show($petition)
    {
        $status    = Status::all();

        $petition = Petition::where('number', $petition)->first();

        return view('petition', compact('petition', 'status'));
    }
}
