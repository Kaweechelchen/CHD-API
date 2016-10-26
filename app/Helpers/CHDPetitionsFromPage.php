<?php

namespace CHD\Helpers;

use Exception;

class CHDPetitionsFromPage
{
    protected $app;
    protected $url;
    protected $webPage;

    public function __construct($app)
    {
        $this->app = $app;
        $this->url = $app['CHDScrapePath']->get(
            $app['CHD']['list']['type'],
            $app['CHD']['list']['paginationArguments']
        );
    }
    public function get($page)
    {
        $this->webPage = $this->app['webRequest']->get($this->url.$page);

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
            echo 'Couldn\'t find petitions table';
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
            if ($this->app['debug'] && $key > 0) {
                continue;
            }
            $petition       = $this->handlePetitionMetaData($rawPetition['meta']);
            $petition['id'] = $this->getId($rawPetition['info']);
            $petitions[]    = $petition;
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
            'authors'    => explode(', ', $metaMatches['author']),
            'submission' => strtotime($metaMatches['submission']),
            'status'     => $metaMatches['status'],
        ];
    }

    protected function getId($data)
    {
        $patitionIDsPattern = '/action=doPetitionDetail&amp;id=(?P<id>\d*)">/';
        if (!preg_match($patitionIDsPattern, $data, $patitionID)) {
            throw new Exception('couldn\'t find patition ID');
        }

        return $patitionID['id'];
    }
}
