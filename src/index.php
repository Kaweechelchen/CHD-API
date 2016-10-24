<?php

    // https://regex101.com/r/lA2tT6/1
    // https://petition.parliament.uk/petitions.json?state=open

    const HOST = 'http://chd.lu';

    function processPage($url)
    {
        echo 'getting page:'.PHP_EOL.$url.PHP_EOL.'...';

        $data = file_get_contents($url);

        echo 'DONE'.PHP_EOL;

        return trim(preg_replace('/\s+/', ' ', $data));
    }

    $file = 'petitions.json';
    file_put_contents($file, json_encode(getPetitions()));

    function handlePageSignatures($signaturesRaw)
    {
        $signatures = [];

        foreach ($signaturesRaw as $signature) {
            $signatureMetaPattern  = '/(?:(?:<td[^>]*)>(?P<data>.[^<]*))<\/td>/';
            if (!preg_match_all($signatureMetaPattern, $signature, $signatureMeta)) {
                throw new Exception('couldn\'t find any signature data');
            }

            switch (count($signatureMeta['data'])) {
                case 1:
                    $signatureMeta = false;
                    break;
                case 4:
                    $signatureMeta = [
                        'lastname' => $signatureMeta['data'][0],
                        'fistname' => $signatureMeta['data'][1],
                        'city'     => $signatureMeta['data'][2],
                        'zip'      => trim(strtolower($signatureMeta['data'][3]), 'l-'),
                    ];
                    break;
                default:
                    throw new Exception('unknown amount of signature details');

            }

            $signatures[] = $signatureMeta;
        }

        return $signatures;
    }

    function getSignatures($url, $page = 1)
    {
        $data = processPage($url.$page);

        $signatures = false;

        $signaturePattern      = '/<tr class="table_column_content">(?:\ )?(?P<signature>.*?)(?:\ )?<\/tr>/';
        if (preg_match_all($signaturePattern, $data, $signature)) {
            $signature = $signature['signature'];

            $signatures = handlePageSignatures($signature);

            if ($page == 1) {
                $paginationPathPattern = '/for="pageNumber"[^\/]*\/\s*(?P<lastPage>\d+)/';

                if (preg_match($paginationPathPattern, $data, $lastPage)) {
                    $lastPage = $lastPage['lastPage'];

                    for ($page = 2; $page <= $lastPage; ++$page) {
                        // TODO remove after debugging / testing
                        /*if ($page > 1) {
                            continue;
                        }*/

                        array_merge($signatures, getSignatures($url, $page));
                    }
                }
            }
        }

        return $signatures;
    }

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

        $paginationURLPattern = '/<form id="petitionSearchForm" action="(?P<petitionURL>.*?)"/';

        if (!preg_match($paginationURLPattern, $data, $petitionURL)) {
            throw new Exception('couldn\'t find last page');
        }

        $petitionURL = $petitionURL['petitionURL'];

        $paginationURL = '?type=TOUTES&etat=TOUS&sousEtat=TOUS&sortDirection=DESC&sortField=dateDepot&pageNumber=';

        $lastPage = $lastPage['lastPage'];

        $total = 0;

        $petitions = [];

        for ($page = 1; $page <= $lastPage; ++$page) {
            // TODO remove after debugging / testing
            /*if ($page != 2) {
                continue;
            }*/

            $petitions = array_merge($petitions, processPetitionsPage(HOST.$petitionURL, $paginationURL.$page));
        }

        return $petitions;
    }

    function processPetitionsPage($petitionURL, $pageURL)
    {
        $data = processPage($petitionURL.$pageURL);

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
            /*if ($key > 0) {
                continue;
            }*/
            $metaData               = handleMeta($rawPetition['meta']);
            $info                   = handleInfo($rawPetition['info']);
            $signatureURL           = str_replace('listPetitionRole', 'PetitionSignatureList/p=petition_id='.$info['id'], $petitionURL).'?sortDirection=DESC&pageNumber=';
            $petition               = [];
            $petition               = array_merge($petition, $metaData);
            $petition               = array_merge($petition, $info);
            $petition['signatures'] = getSignatures($signatureURL);

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

    // signatures:
    //   http://chd.lu
    //   /wps
    //   /portal
    //   /public
    //   /!ut
    //   /p
    //   /b1
    //   /jctLCsIwFIXhtbiC3MTkNh3eaPOgKNVgsZlIBkUKfUzE9VsXIPbMDvwfS6zTikuFBUh2Z2nO7-GZX8My5_H7Ez4E2La1wpFxqIBcxLpoAjgq1qBbAx4U-QDCg0WE0PhjibXc145v80LreHV0qsryZiDAgcfqYgQEsc3DjxH887Gf2dkvU8-mNFqrafcBquUxIQ!!
    //   /dl4
    //   /d5
    //   /L2dBISEvZ0FBIS9nQSEh
    //   /pw
    //   /Z7_5T6UAKA71GR720A872MA2Q1GS5
    //   /ren
    //   /p=petition_id=771
    //   /p=ePetition=PetitionSignatureList/-/
    //
    //   http://chd.lu
    //   /wps
    //   /portal
    //   /public
    //   /!ut
    //   /p
    //   /b1
    //   /jcvRboIwGIbhK1r6t9KWHv4glAp1QMUIJwQT45gImLAdePVjF7DM7-xL3oc0pPY59biQ4JETacbuu792Sz-N3fD7G9EyiI_HmGkMtOCA2olU5gY0yjWo14AajokBlkAsBJg82SqReptU09c8831XarSRUlUABkLqoiJgYNhrHv4Ywn_eXUayT6b7hdRrKlt-EBWmKKkuJQ//   P0JbPICqodJwdyAq91nzDtXbjZZXa0T_Wef2Q6tCp_Rrcl7elO2WnJlIkf4QOd39zmtzm58-ksvsrz0F9xthVW5N4Mcexvix_O3TgC
    //   /dl4
    //   /d5
    //   /L2dBISEvZ0FBIS9nQSEh
    //   /pw
    //   /Z7_5T6UAKA71GR720A872MA2Q1GS5
    //   /ren
    //   /p=petition_id=771
    //   /p=ePetition=PetitionSignatureList/-/
    //   ?sortDirection=DESC&pageNumber=2

    // http://chd.lu/wps/portal/public/!ut/p/b1/jcvLDoIwEIXhR5rpSC8sB6WlIRqhQCwbwoIYEsCN8fnFBzB6dif5P-ghGikSqTQmcIN-G1_zfXzOj21cPr9XA6HtOkuOM6cksguq1FePjvUexD0QXnLhkQq0SqG_FqdUlcmhdOI_T8aE2vE5T9M2Q49HEfIqI_T0n8cvY_zlw7TBpXisE8Q91YNsVMsla-FqTchG05mpEi5IaCDmsPaLteZUvQGw2zMU/dl4/d5/L2dBISEvZ0FBIS9nQSEh/pw/Z7_5T6UAKA71GR720A872MA2Q1GS5/ren/p=petition_id=771/p=ePetition=PetitionSignatureList/-/?sortDirection=DESC&pageNumber=1#Z7_5T6UAKA71GR720A872MA2Q1GS5

    // TODO: remove before PROD
    echo PHP_EOL;

// <tr class="table_column_content">(?:(?:[^>]*)>(.[^<]*))(.*)<\/tr>
// <tr class="table_column_content">
//<td style="border-width : 1px 0 1px 1px;">PIMOLTHAI</td><td style="border-width : 1px 0 1px 0;">Patttaraporn</td><td style="border-width : 1px 0 1px 0;">Luxembourg</td><td style="border-width : 1px 1px 1px 0;">L-1326</td></tr>
//
//
//
//(?:(?:<td[^>]*)>(.[^<]*))
//<td style="border-width : 1px 0 1px 1px;">PIMOLTHAI</td>
//<td style="border-width : 1px 0 1px 0;">Patttaraporn</td>
//<td style="border-width : 1px 0 1px 0;">Luxembourg</td>
//<td style="border-width : 1px 1px 1px 0;">L-1326</td>
