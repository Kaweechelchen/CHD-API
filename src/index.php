<?php

    // https://regex101.com/r/lA2tT6/1

    $data = file_get_contents('http://www.chd.lu/wps/portal/public/SignerPetition');

    $data = trim(preg_replace('/\s+/', ' ', $data));

    $startString = '<!-- BEGIN petitionElementsList -->';

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
    }
