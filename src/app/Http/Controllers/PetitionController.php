<?php

namespace App\Http\Controllers;

use App\Petition;

class PetitionController extends Controller
{
    public function index($page = 1)
    {
        $offset         = ((int) $page - 1) * env('ITEMS_PER_PAGE');
        $count          = Petition::count();
        $petitions      = Petition::includingStatus(env('ITEMS_PER_PAGE'), $offset);

        return view(
            'petitions',
            compact(
                'petitions',
                'count',
                'page'
            )
        );
    }

    public function show($petition)
    {
        $petition = Petition::where('number', $petition)->first();

        return view(
            'petition',
            compact(
                'petition'
            )
        );
    }
}
