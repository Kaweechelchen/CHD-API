<?php

namespace App\Http\Controllers;

use App\Petition;

class PetitionController extends Controller
{
    public function index()
    {
        $petitions = Petition::all();

        return $petitions;
    }

    public function show(Petition $petition)
    {
        return $petition;
    }
}
