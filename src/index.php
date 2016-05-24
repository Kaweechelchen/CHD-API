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

    //print_r($rows);

    $petitions = [];

    $petition = [];

    $count = 1;

    foreach ($rows as $key => $row) {
        switch ($count) {
            case 1:
                $petition['meta'] = $row;
                break;
            case 2:
                $petition['data'] = $row;
                break;
            case 3:
                $petitions[] = $petition;
                $petition    = [];
                $count       = 0;
                break;
        }

        ++$count;
    }

    print_r($petitions);

    die();

    /*

    $petitionsPattern = '/<tr>(.*?)<\/tr>(?: )<tr>(?: )<td>&nbsp;<\/td>(?: )<\/tr>(?: )/';

    if (!preg_match_all($petitionsPattern, $table, $petitions, PREG_PATTERN_ORDER)) {
        echo 'something is wrong2...';
        exit;
    }

    $petitions = $petitions[1];

    foreach ($petitions as $key => $petition) {
        $petition = trim($petition);

        $rows = explode('</tr> <tr>', $petition);

        foreach ($rows as $rowKey => $row) {
            $rows[$rowKey] = trim($row);
        }

        $petitions[$key] = $rows;
    }

    // <td[^>]*>(?:[^<])<b>Dépôt: (?P<depot>[^<]*)(.*)<\/td>

    print_r($petitions);

    */
