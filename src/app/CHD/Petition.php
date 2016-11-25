<?php

namespace App\CHD;

class Petition
{
    public function get($id)
    {
        $data = app('Request')->get(env('CHD_HOST').env('CHD_PETITION_URL').$id);

        $startString = '<div id="PRINT_EPETITION_DETAIL">';

        $start = strpos($data, $startString) + strlen($startString);
        $stop  = strpos($data, '<div class="contentType3Items">');

        $petitionString = substr(
            $data,
            $start,
            $stop - $start
        );

        $petitionString = trim($petitionString);

        $metaPattern = '/.*Auteur: <\/span> <span class="property_value">(?P<author>[^<]*)(.*électroniques: <\/span> <span class="property_value">(?P<signatures_electronic>\d+))?.*Dépôt: <\/span> <span class="property_value">(?P<submission>\d{2}-\d{2}-\d{4}).*(Signatures papier: <\/span> <span class="property_value">(?P<signatures_paper>\d+).*)?<span class="property_value">(?P<status>[^<]*).*<span class="subject_header">(?P<name>[^<]*)<\/span> -(?P<description>[^<]*).*<tbody>(?P<events_table>.*)<\/tbody>/';

        dd($petitionString);

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
            'signatures_electronic' => (int) trim($metaMatches['signatures_electronic']),
            'signatures_paper'      => (int) trim($metaMatches['signatures_paper']),
        ];
    }
}
