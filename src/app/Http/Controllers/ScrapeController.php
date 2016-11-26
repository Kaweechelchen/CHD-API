<?php

namespace App\Http\Controllers;

use App\Author;
use App\Petition;
use App\Status;
use App\PetitionStatus;

class ScrapeController extends Controller
{
    public function index()
    {
        app('Path')->init();
        app('PetitionsPage')->init();

        $page_number = 47;

        $petitionsOnPage = app('PetitionsPage')->get($page_number);

        foreach ($petitionsOnPage as $index_on_page => $petition) {
            Petition::updateOrCreate(
                [
                    'id' => $petition['id'],
                ],
                [
                    'number'          => $petition['number'],
                    'submission_date' => $petition['submission_date'],
                    'page_number'     => $page_number,
                    'index_on_page'   => $index_on_page,
                ]
            );

            $this->attachAuthors(
                $petition['authors'],
                Petition::findOrFail($petition['id'])->id
            );
        }

        $petitions = Petition::all();

        while ($petition = $petitions->pop()) {
            $petitionDetails = app('Petition')->get($petition->id);
            $this->attachStatus(
                $petitionDetails['status'],
                $petition->id
            );
            $petition->name             = $petitionDetails['name'];
            $petition->description      = $petitionDetails['description'];
            $petition->paper_signatures = $petitionDetails['paper_signatures'];
            $petition->save();
        }

        return $petitionDetails;
    }

    protected function statusId($status)
    {
        return Status::updateOrCreate(
            ['status' => $status]
        )->id;
    }

    protected function attachAuthors($authors, $petitionId)
    {
        foreach ($authors as $author) {
            Author::updateOrCreate(
                [
                    'name'        => $author,
                    'petition_id' => $petitionId,
                ]
            );
        }
    }

    protected function attachStatus($status, $petitionId)
    {
        Status::updateOrCreate(
            ['status' => $status],
            ['status' => $status]
        );

        $statusId = Status::where('status', $status)->firstOrFail()->id;

        $lastPetitionStatus = PetitionStatus::where('petition_id', $petitionId)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastPetitionStatus == null || $lastPetitionStatus->status_id != $statusId) {
            PetitionStatus::create(
                [
                    'petition_id' => $petitionId,
                    'status_id'   => $statusId,
                ]
            );
        }
    }
}
