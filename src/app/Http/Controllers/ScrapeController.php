<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Statuses;
use App\Petitions;

class ScrapeController extends Controller
{
    protected $cachedStatuses;

    public function index()
    {
        //return app('Path')->get('test', 'bla');

        app('Path')->init();
        app('PetitionsPage')->init();

        return app('Petition')->get(22);

        //return app('PetitionsPage')->get(2);

        $petitionsOnPage = app('PetitionsPage')->get(2);

        //return $petitionsOnPage;

        foreach ($petitionsOnPage as $key => $petition) {
            Log::info($petition['id']);
            $petition['id'] = 22;

            $petition = array_merge(
                $petition,
                app('Petition')->get($petition['id'])
            );

            Petitions::updateOrCreate(
                ['id'        => $petition['id']],
                ['status_id' => $this->statusId($petition['status'])],
                ['number'    => 'hehe']
            );
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

    protected function authors($authorString)
    {
    }
}
