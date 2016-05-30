<?php

    // https://regex101.com/r/lA2tT6/1

    //$data = file_get_contents('http://www.chd.lu/wps/portal/public/SignerPetition');

    $data = file_get_contents('../source/petitions.html');

    $data = file_get_contents('../source/703.html');

    $data = trim(preg_replace('/\s+/', ' ', $data));

    /*$startString = '<!-- BEGIN petitionElementsList -->';

    $start = strpos($data, $startString) + strlen($startString);
    $stop  = strpos($data, '<!-- END petitionElementsList -->');

    $petitionsString = substr(
        $data,
        $start,
        $stop - $start
    );

    $tablePattern = '/<tbody> <tr>(.*)<\/tr> <\/tbody>/';

    if (!preg_match($tablePattern, $petitionsString, $table)) {
        echo 'something is wrong...';
        exit;
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
        $petitions[] = array_merge(
            handleMeta($rawPetition['meta']),
            handleInfo($rawPetition['info'])
        );
    }

    print_r($petitions);

    function handleInfo($info)
    {
        $infoPattern = '/<td[^>]*> <a href="(?P<link>[^"]*).*Pétition publique (?P<number>\d+).*électroniques: (?P<online_signatures>\d+).*<\/td>/';

        if (!preg_match($infoPattern, $info, $infoMatches)) {
            throw new Exception('metadata not matching');
        }

        return [
            'link'              => $infoMatches['link'],
            'number'            => $infoMatches['number'],
            'online_signatures' => $infoMatches['online_signatures'],
        ];
    }

    function handleMeta($meta)
    {
        $metaPattern = '/<td[^>]*> <b>Dépôt: (?P<submission>[^<]*)(?:.*)Auteur: (?P<author>[^<]*)(?:.*)signature: (?P<signature_end>[^<]*).*<\/td>/';

        if (!preg_match($metaPattern, $meta, $metaMatches)) {
            throw new Exception('metadata not matching');
        }

        return [
            'authors'       => explode(', ', $metaMatches['author']),
            'submission'    => strtotime($metaMatches['submission']),
            'signature_end' => strtotime($metaMatches['signature_end']),
        ];
    }*/

    $startString = '<div id="PRINT_EPETITION_DETAIL">';

    $start = strpos($data, $startString) + strlen($startString);
    $stop  = strpos($data, '<div class="contentType3Items">');

    $petitionString = substr(
        $data,
        $start,
        $stop - $start
    );

    $petitionString = trim($petitionString);

    $metaPattern = '/.*Auteur: <\/span> <span class="property_value">(?P<author>[^<]*).*électroniques: <\/span> <span class="property_value">(?P<signatures_electronic>\d+).*Dépôt: <\/span> <span class="property_value">(?P<submission>[^<]*).*Signatures papier: <\/span> <span class="property_value">(?P<signatures_paper>[^<]*).*<span class="property_value">(?P<state>[^<]*).*<span class="subject_header">(?P<name>[^<]*)<\/span> - (?P<description>[^<]*).*<tbody>(?P<events_table>.*)<\/tbody>/';

    if (!preg_match($metaPattern, $petitionString, $metaMatches)) {
        throw new Exception('metadata not matching');
    }

    $events = explode('</tr> <tr', $metaMatches['events_table']);

    $eventPattern = '/<td[^>]*>(?P<date>.*?)<\/td> <td[^>]*>(?P<event>.*?)<\/td> <td[^>]*>(?P<link>.*?)<\/td>/';

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

            $eventMatches[$key] = br2nl($eventMatch);
        }

        $linkPattern = '/openWindow\(\'(?P<link>[^\']*)\'.*">(?P<name>[^<]*)/';

        if (preg_match($linkPattern, $eventMatches['link'], $linkMatches)) {
            $link = [
                'name' => $linkMatches['name'],
                'link' => $linkMatches['link'],
            ];
        } else {
            $link = [];
        }

        $event = [
            'date'  => strtotime($eventMatches['date']),
            'event' => br2nl($eventMatches['event']),
            'links' => $link,
        ];

        print_r($event);
    }

    function br2nl($string)
    {
        return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
    }
