<?php

namespace App\Http\Controllers;

use App\Petition;
use App\Status;
use App\Signature;
use Illuminate\Support\Facades\DB;

class PetitionController extends Controller
{
    public function index()
    {
        app('Stats')->init();

        dd(app('Stats')->days());

        //$petitions = Petition::all();
        /*$petitions = Petition::orderBy('number', 'desc')
            ->limit(20)
            ->get();*/

        $count = Signature::whereBetween(
            'created_at', [
                date('Y-m-d H:i:s', strtotime('-4 days')),
                date('Y-m-d H:i:s'),
            ]
        )->count();

        dd($count);

        $petitions = DB::select(
            'SELECT
                petitions.*,
                statuses.status,
                ps.created_at AS status_update
            FROM
                petition_statuses ps
                INNER JOIN (
                    SELECT
                        petition_id,
                        MAX(created_at) AS created_at

                    FROM
                        petition_statuses

                    GROUP BY
                        petition_id
                ) AS max

                USING (
                    petition_id,
                    created_at
                )

                INNER JOIN statuses
                    ON ps.status_id = statuses.id
                INNER JOIN petitions
                    ON ps.petition_id = petitions.id

            ORDER BY
                petitions.number DESC

            LIMIT 10'
        );

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
