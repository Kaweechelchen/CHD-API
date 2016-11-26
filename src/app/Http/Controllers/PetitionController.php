<?php

namespace App\Http\Controllers;

use App\Petition;
use App\Status;

class PetitionController extends Controller
{
    public function index()
    {
        $petitions = Petition::all();
        $status    = Status::all();

        return view('petitions', compact('petitions', 'status'));
    }

    public function show(Petition $petition)
    {
        return $petition;
    }
}
