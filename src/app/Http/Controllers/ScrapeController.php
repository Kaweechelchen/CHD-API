<?php

namespace App\Http\Controllers;

use App\Author;
use App\Petition;
use App\Status;
use App\PetitionStatus;
use App\Event;
use App\Link;

class ScrapeController extends Controller
{
    public function index()
    {
        app('Path')->init();
        app('PetitionsFromPage')->init();
        app('PetitionPages')->init();

        foreach (app('PetitionPages')->get() as $page) {
            if ($page == 47) {
                $this->handlePetitionPage($page);
            }
        }
    }

    protected function handlePetitionPage($page)
    {
        $petitionsOnPage = app('PetitionsFromPage')->get($page);

        foreach ($petitionsOnPage as $index_on_page => $petition) {
            Petition::updateOrCreate(
                [
                    'id' => $petition['id'],
                ],
                [
                    'number'          => $petition['number'],
                    'submission_date' => $petition['submission_date'],
                    'page_number'     => $page,
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

            foreach ($petitionDetails['events'] as $event) {
                Event::updateOrCreate(
                    [
                        'petition_id' => $petition->id,
                        'datetime'    => $event['datetime'],
                        'event'       => $event['event'],
                    ],
                    [
                        'petition_id' => $petition->id,
                        'datetime'    => $event['datetime'],
                        'event'       => $event['event'],
                    ]
                );

                $eventId = Event::where(
                    [
                        'petition_id' => $petition->id,
                        'datetime'    => $event['datetime'],
                        'event'       => $event['event'],
                    ]
                )->first();

                if (is_null($eventId)) {
                    dd(['petition_id' => $petition->id,
                    'datetime'        => $event['datetime'],
                    'name'            => $event['name'], ]);
                }

                $eventId = $eventId->id;

                foreach ($event['links'] as $link) {
                    Link::updateOrCreate(
                        [
                            'event_id'  => $eventId,
                            'name'      => $link['name'],
                        ],
                        [
                            'event_id'  => $eventId,
                            'name'      => $link['name'],
                            'url'       => $link['url'],
                        ]
                    );
                }
            }
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