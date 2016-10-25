<?php

namespace CHD\Helpers;

class CHDPetitionList
{
    protected $app;
    protected $url;

    public function __construct($app)
    {
        $this->app = $app;
        $this->url = $app['CHDScrapePath']->get(
            $app['CHD']['list']['type'],
            $app['CHD']['list']['paginationArguments']
        );
    }
    public function getIDs()
    {
        return $this->url;
        //$paginationPathPattern = '/action="(?P<pagination>[^#]*)/';

        if (!preg_match($paginationPathPattern, $data, $paginationPath)) {
            throw new Exception('couldn\'t find the pagination path');
        }
        $paginationPath = $paginationPath['pagination'];

        $app['log']->debug($paginationPath);

        $paginationPathPattern = '/for="pageNumber"[^\/]*\/\s*(?P<lastPage>\d+)/';

        if (!preg_match($paginationPathPattern, $data, $lastPage)) {
            throw new Exception('couldn\'t find last page');
        }

        $paginationURLPattern = '/<form id="petitionSearchForm" action="(?P<petitionURL>.*?)"/';

        if (!preg_match($paginationURLPattern, $data, $petitionURL)) {
            throw new Exception('couldn\'t find last page');
        }

        $petitionURL = $petitionURL['petitionURL'];

        $paginationURL = '?type=TOUTES&etat=TOUS&sousEtat=TOUS&sortDirection=ASC&sortField=dateDepot&pageNumber=';

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
}
