<?php

    // https://regex101.com/r/lA2tT6/1
    // https://petition.parliament.uk/petitions.json?state=open

    const HOST = 'http://chd.lu';

    function processPage($url)
    {
        $data = file_get_contents($url);

        return trim(preg_replace('/\s+/', ' ', $data));
    }

    print_r(getPetitions());

    function getPetitions()
    {
        $data                  = processPage(HOST.'/wps/portal/public/RolePetition');
        $paginationPathPattern = '/action="(?P<pagination>[^#]*)/';

        if (!preg_match($paginationPathPattern, $data, $paginationPath)) {
            throw new Exception('couldn\'t find the pagination path');
        }
        $paginationPath = $paginationPath['pagination'];

        $paginationPathPattern = '/for="pageNumber"[^\/]*\/\s*(?P<lastPage>\d+)/';

        if (!preg_match($paginationPathPattern, $data, $lastPage)) {
            throw new Exception('couldn\'t find last page');
        }

        $paginationURLPattern = '/<form id="petitionSearchForm" action="(?P<paginationURL>.*?)"/';

        if (!preg_match($paginationURLPattern, $data, $paginationURL)) {
            throw new Exception('couldn\'t find last page');
        }

        $paginationURL = rtrim($paginationURL['paginationURL'], '/');

        $paginationURL = HOST.$paginationURL.'/?type=TOUTES&etat=TOUS&sousEtat=TOUS&sortDirection=DESC&sortField=dateDepot&pageNumber=';

        $lastPage = $lastPage['lastPage'];

        $total = 0;

        $petitions = [];

        for ($page = 1; $page <= $lastPage; ++$page) {
            // TODO remove after debugging / testing
            if ($page > 1) {
                continue;
            }
            $petitions = array_merge($petitions, processPetitionsPage($paginationURL.$page));
        }

        print_r($petitions);
    }

    function processPetitionsPage($page)
    {
        $data = processPage($page);

        $startString = '<!-- BEGIN petitionElementsList -->';

        $start = strpos($data, $startString) + strlen($startString);
        $stop  = strrpos($data, '<!-- END petitionElementsList -->');

        $petitionsString = substr(
            $data,
            $start,
            $stop - $start
        );

        $tablePattern = '/<tbody> <tr>(.*)<\/tr> <\/tbody>/';

        if (!preg_match($tablePattern, $petitionsString, $table)) {
            echo 'something is wrong...';
        }

        $table = trim($table[1]);

        $rows = explode('</tr> <tr>', $table);

        foreach ($rows as $key => $row) {
            $rows[$key] = trim($row);
        }

        $rawPetitions = [];

        $rawPetition = [];

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
            // TODO remove after debugging / testing
            if ($key > 0) {
                continue;
            }
            $metaData = handleMeta($rawPetition['meta']);
            $info     = handleInfo($rawPetition['info']);
            $petition = [];
            $petition = array_merge($petition, $metaData);
            $petition = array_merge($petition, $info);

            if (isset($info['link'])) {
                $details  = handlePetitionDetails(HOST.$info['link']);
                $petition = array_merge($petition, $details);
            }

            $petitions[] = $petition;
        }

        return $petitions;
    }

    function handleInfo($info)
    {
        $infoPattern = '/<td[^>]*> <a href="(?P<link>[^"]*).*Pétition (?:publique|ordinaire) (?P<number>\d+)(.*électroniques: (?P<online_signatures>\d+))?.*<\/td>/';

        if (!preg_match($infoPattern, $info, $infoMatches)) {
            print_r($info);
            die();

            throw new Exception('info not matching');
        }

        if (!isset($infoMatches['online_signatures'])) {
            $infoMatches['online_signatures'] = null;
        }

        return [
            'link'              => html_entity_decode($infoMatches['link']),
            'id'                => explode('id=', $infoMatches['link'])[1],
            'number'            => $infoMatches['number'],
            'online_signatures' => $infoMatches['online_signatures'],
        ];
    }

    function handleMeta($meta)
    {
        $metaPattern = '/<td[^>]*> <b>Dépôt: (?P<submission>[^<]*)(?:.*)Auteur: (?P<author>[^<]*)(?:.*)<b>(?P<status>[^<]*).*<\/td>/';

        if (!preg_match($metaPattern, $meta, $metaMatches)) {
            throw new Exception('metadata not matching');
        }

        return [
            'authors'    => explode(', ', $metaMatches['author']),
            'submission' => strtotime($metaMatches['submission']),
            'status'     => $metaMatches['status'],
        ];
    }

    function handlePetitionDetails($url)
    {
        $data = processPage($url);

        $startString = '<div id="PRINT_EPETITION_DETAIL">';

        $start = strpos($data, $startString) + strlen($startString);
        $stop  = strpos($data, '<div class="contentType3Items">');

        $petitionString = substr(
            $data,
            $start,
            $stop - $start
        );

        $petitionString = trim($petitionString);

        $metaPattern = '/.*Auteur: <\/span> <span class="property_value">(?P<author>[^<]*)(.*électroniques: <\/span> <span class="property_value">(?P<signatures_electronic>\d+))?.*Dépôt: <\/span> <span class="property_value">(?P<submission>[^<]*).*(Signatures papier: <\/span> <span class="property_value">(?P<signatures_paper>[^<]*).*)?<span class="property_value">(?P<status>[^<]*).*<span class="subject_header">(?P<name>[^<]*)<\/span> -(?P<description>[^<]*).*<tbody>(?P<events_table>.*)<\/tbody>/';

        if (!preg_match($metaPattern, $petitionString, $metaMatches)) {
            throw new Exception('metadata not matching');
        }

        $patterns = [
            'event' => '/<td[^>]*>(?P<date>.*?)<\/td> <td[^>]*>(?P<event>.*?)<\/td> <td[^>]*>(?P<link>.*?)<\/td>/',
        ];

        return [
            'name'                  => trim($metaMatches['name']),
            'description'           => trim($metaMatches['description']),
            'status'                => trim($metaMatches['status']),
            'author'                => trim($metaMatches['author']),
            'signatures_electronic' => trim($metaMatches['signatures_electronic']),
            'signatures_paper'      => trim($metaMatches['signatures_paper']),
            'events'                => handleEvents($metaMatches['events_table']),
        ];
    }

    function handleEvents($eventsTable)
    {
        $newEvents    = [];
        $eventPattern = '/<td[^>]*>(?P<date>.*?)<\/td> <td[^>]*>(?P<event>.*?)<\/td> <td[^>]*>(?P<link>.*?)<\/td>/';

        $events = explode('</tr> <tr', $eventsTable);

        foreach ($events as $key => $event) {
            if ($key == 0) {
                continue;
            }

            if (!preg_match($eventPattern, $event, $eventMatches)) {
                print_r($event);
                die();

                throw new Exception('eventdata not matching');
            }

            foreach ($eventMatches as $key => $eventMatch) {
                if ($key == 0) {
                    continue;
                }

                $eventMatches[$key] = br2nl($eventMatch);
            }

            $linkPattern = '/openWindow\(\'(?P<link>[^\']*)\'.*">(?P<name>[^<]*)/';

            if (preg_match($linkPattern, $eventMatches['link'], $linkMatches)) {
                $link = [
                    'name' => $linkMatches['name'],
                    'link' => HOST.$linkMatches['link'],
                ];
            } else {
                $link = null;
            }

            $event = [
                'date'  => strtotime($eventMatches['date']),
                'event' => br2nl($eventMatches['event']),
                'links' => $link,
            ];

            $newEvents[] = $event;
        }

        return $newEvents;
    }

    function br2nl($string)
    {
        return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
    }
