<?php

namespace App\CHD;

use Exception;

class Petition
{
    protected $petitionHTML;
    protected $id;

    public function get($id)
    {
        $this->id = $id;
        $data     = app('Request')->get(str_replace('{{idPetition}}', $id, env('CHD_LINK_PETITION')));

        $startString = '<div id="PRINT_EPETITION_DETAIL">';

        $start = strpos($data, $startString) + strlen($startString);
        $stop  = strpos($data, '<div class="contentType3Items">');

        $this->petitionHTML = trim(
            substr(
                $data,
                $start,
                $stop - $start
            )
        );

        return [
            'name'             => $this->name(),
            'description'      => $this->description(),
            'paper_signatures' => $this->paperSignatures(),
            'status'           => $this->status(),
            'events'           => $this->events(),
        ];
    }

    protected function name()
    {
        $namePattern = '/<span class="subject_header">(?P<name>[^<]*)<\/span>/';
        if (!preg_match($namePattern, $this->petitionHTML, $name)) {
            throw new Exception('Couldn\'t find the name of petition ID '.$this->id);
        }

        return trim($name['name']);
    }

    protected function description()
    {
        $descriptionPattern = '/<span class="subject_header">(?:[^<]*)<\/span>(?:\ -)?(?P<description>[^<]*)<br\/> <\/div>/';
        if (!preg_match($descriptionPattern, $this->petitionHTML, $description)) {
            throw new Exception('Couldn\'t find the description of petition ID '.$this->id);
        }

        return trim($description['description']);
    }

    protected function paperSignatures()
    {
        $paperSignaturesPattern = '/<span class="property_name">Signatures papier(?::\ )<\/span>(?:\ )<span class="property_value">(?P<paper_signatures>\d+)<\/span>/';
        if (!preg_match($paperSignaturesPattern, $this->petitionHTML, $paper_signatures)) {
            return null;
        }

        return (int) $paper_signatures['paper_signatures'];
    }

    protected function status()
    {
        $statusPattern = '/" > <span class="property_value">(?P<status>[^<]*)/';
        if (!preg_match($statusPattern, $this->petitionHTML, $status)) {
            throw new Exception('Couldn\'t find the status of petition ID '.$this->id);
        }

        return trim($status['status']);
    }

    protected function events()
    {
        $newEvents    = [];
        $eventPattern = '/<td[^>]*>(?P<date>.*?)<\/td> <td[^>]*>(?P<event>.*?)<\/td> <td[^>]*>(?P<url>.*?)<\/td>/';

        $events = explode('</tr> <tr', $this->petitionHTML);

        foreach ($events as $key => $event) {
            if ($key == 0) {
                continue;
            }

            if (!preg_match($eventPattern, $event, $eventMatches)) {
                throw new Exception('eventdata not matching');
            }

            foreach ($eventMatches as $key => $eventMatch) {
                if ($key == 0) {
                    continue;
                }

                $eventMatches[$key] = $this->br2nl($eventMatch);
            }

            $linkInEventPattern = '/\/wps\/portal\/public\/(?:.*)petition_id=(?P<PetitionID>\d+)\/p=ePetition=PetitionDetail\/-\//';

            if (preg_match($linkInEventPattern, $eventMatches['event'], $linkInEvent)) {
                $newLink = '/wps/portal/public/PetitionDetail?action=doPetitionDetail&id='.$linkInEvent['PetitionID'];

                $eventMatches['event'] = str_replace($linkInEvent[0], $newLink, $eventMatches['event']);
            }

            $linkPattern = '/openWindow\(\'(?P<url>[^\']*)\'.*">(?P<name>[^<]*)/';

            if (preg_match($linkPattern, $eventMatches['url'], $linkMatches)) {
                $link[] = [
                    'name' => $linkMatches['name'],
                    'url'  => env('CHD_HOST').$linkMatches['url'],
                ];
            } else {
                $link = [];
            }

            $event = [
                'datetime' => date('Y-m-d H:i:s', strtotime($eventMatches['date'])),
                'event'    => $this->br2nl($eventMatches['event']),
                'links'    => $link,
            ];

            $newEvents[] = $event;
        }

        return $newEvents;
    }

    protected function br2nl($string)
    {
        return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
    }
}
