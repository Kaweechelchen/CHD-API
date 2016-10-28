<?php

namespace CHD\Helpers;

class CHDPetition
{
    protected $app;
    public function __construct($app)
    {
        $this->app = $app;
    }

    public function get($petition)
    {
        $petition['daniel'] = 'test';

        $data = $this->app['webRequest']->get($this->app['CHD']['petition']['url'].$petition['id']);

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
            'events'                => $this->app['CHDPetitionEvents']->get($metaMatches['events_table']),
        ];

        return $petition;
    }
}
