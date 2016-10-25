<?php

namespace CHD\Helpers;

class CHDPetitionList
{
    public function getIDs($app)
    {
        //$data                  = $app['webRequest']->get($app['CHD']['host'].'/wps/portal/public/RolePetition');
        $data                  = $app['webRequest']->get(__DIR__.'/../../../old/source/petitions.html');
        $scrapePathPattern     = '/action="(?P<scrapePath>[^#]*=(?P<type>.[^\/]*)\/-\/)/';

        if (!preg_match($scrapePathPattern, $data, $scrapePath)) {
            throw new Exception('couldn\'t find the scrape Path');
        }

        $scrapePath = str_replace($scrapePath['type'], '%s', $scrapePath['scrapePath']);

        $app['scrapePath'] = $app['CHD']['host'].$scrapePath.'%s';

        die(sprintf($scrapePath, 'TYPETYPETYPE', 'ARGUMENTSARGUMENTS'));

        die($scrapePath);

        //$paginationPathPattern = '/action="(?P<pagination>[^#]*)/';

        if (!preg_match($paginationPathPattern, $data, $paginationPath)) {
            throw new Exception('couldn\'t find the pagination path');
        }
        $paginationPath = $paginationPath['pagination'];

        $num      = 5;
        $location = 'tree';

        $format = 'There are %d monkeys in the %s';
        echo sprintf($format, $num, $location);

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
