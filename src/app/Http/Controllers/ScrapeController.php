<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Authors;
use App\Petitions;
use App\Statuses;

class ScrapeController extends Controller
{
    protected $cachedStatuses;

    public function index()
    {
        //return app('Path')->get('test', 'bla');

        app('Path')->init();
        app('PetitionsPage')->init();

        //return app('Petition')->get(22);

        //return app('PetitionsPage')->get(2);

        $petitionsOnPage = app('PetitionsPage')->get(2);

        //return $petitionsOnPage;

        foreach ($petitionsOnPage as $key => $petition) {
            Log::info($petition['id']);
            $petition['id'] = 797;

            $petition = array_merge(
                $petition,
                app('Petition')->get($petition['id'])
            );

            $PetitionsId = Petitions::updateOrCreate(
                ['id'        => $petition['id']],
                [
                    'status_id'       => $this->statusId($petition['status']),
                    'number'          => $petition['number'],
                    'description'     => $petition['description'],
                    'name'            => $petition['name'],
                    'submission_date' => $petition['submission_date'],

                ]
            );

            return $petition;

            $this->attachAuthors($petition['authors'], $petition['id']);
        }

        return $petitionsOnPage;

        Log::info('hehe');
    }

    protected function statusId($status)
    {
        return Statuses::updateOrCreate(
            ['status' => $status]
        )->id;
    }

    protected function attachAuthors($authors, $petitionId)
    {
        foreach ($authors as $author) {
            Authors::updateOrCreate(
                [
                    'name'        => $author,
                    'petition_id' => $petitionId,
                ]
            );
        }
    }
}
