<?php

namespace App\CHD;

use Exception;

class PetitionsPage
{
    protected $petitionsPageURL;

    public function init()
    {
        $this->petitionsPageURL = app('Path')->get(
            'listPetitionRole',
            '?type=TOUTES&etat=TOUS&sousEtat=TOUS&sortDirection=ASC&sortField=dateDepot&pageNumber='
        );
    }

    public function get($page)
    {
        $this->webPage = app('Request')->get($this->petitionsPageURL.$page);

        $startString     = '<!-- BEGIN petitionElementsList -->';
        $start           = strpos($this->webPage, $startString) + strlen($startString);
        $stop            = strrpos($this->webPage, '<!-- END petitionElementsList -->');
        $petitionsString = substr(
            $this->webPage,
            $start,
            $stop - $start
        );

        $tablePattern = '/<tbody> <tr>(.*)<\/tr> <\/tbody>/';
        if (!preg_match($tablePattern, $petitionsString, $table)) {
            throw new Exception('Couldn\'t find petitions table');
        }

        $table = trim($table[1]);
        $rows  = explode('</tr> <tr>', $table);
        foreach ($rows as $key => $row) {
            $rows[$key] = trim($row);
        }
        $count = 1;
        foreach ($rows as $key => $row) {
            switch ($count) {
                case 1:
                    $rawPetition['meta'] = $row;
                    break;
                case 2:
                    $rawPetition['info'] = $row;
                    break;
                case 3:
                    $rawPetitions[] = $rawPetition;
                    $rawPetition    = [];
                    $count          = 0;
                    break;
            }
            ++$count;
        }

        $petitions = [];
        foreach ($rawPetitions as $key => $rawPetition) {
            if (env('APP_DEBUG') && $key > 0) {
                //continue;
            }
            $petition           = $this->handlePetitionMetaData($rawPetition['meta']);
            $idAndNumber        = $this->idAndNumber($rawPetition['info']);
            $petition['id']     = $idAndNumber['id'];
            $petition['number'] = $idAndNumber['number'];
            $petitions[]        = $petition;
        }

        return $petitions;
    }

    protected function handlePetitionMetaData($data)
    {
        $metaPattern = '/<td[^>]*> <b>Dépôt: (?P<submission>[^<]*)(?:.*)Auteur: (?P<author>[^<]*)(?:.*)<b>(?P<status>[^<]*).*<\/td>/';

        if (!preg_match($metaPattern, $data, $metaMatches)) {
            throw new Exception('metadata not matching');
        }

        return [
            'authors'         => explode(', ', $metaMatches['author']),
            'submission_date' => date('Y-m-d H:i:s', strtotime($metaMatches['submission'])),
            'status'          => $metaMatches['status'],
        ];
    }

    protected function idAndNumber($data)
    {
        $idAndNumberPattern = '/action=doPetitionDetail&amp;id=(?P<id>\d*)">(?: ?)Pétition (?:publique|ordinaire)(?: ?)(?P<number>\d+)/';
        if (!preg_match($idAndNumberPattern, $data, $idAndNumber)) {
            throw new Exception('couldn\'t find patition ID or Number');
        }

        $idAndNumber['number'] = (int) $idAndNumber['number'];
        $idAndNumber['id']     = (int) $idAndNumber['id'];

        return $idAndNumber;
    }
}
