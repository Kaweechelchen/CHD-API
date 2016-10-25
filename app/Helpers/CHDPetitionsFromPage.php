<?php

namespace CHD\Helpers;

use Exception;

class CHDPetitionsFromPage
{
    protected $app;

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
        $data               = $this->app['webRequest']->get($this->url.$page);
        $patitionIDsPattern = '/action=doPetitionDetail&amp;id=(?P<id>\d*)">/';
        if (!preg_match_all($patitionIDsPattern, $data, $patitionIDs)) {
            throw new Exception('couldn\'t find patition IDs');
        }

        return $patitionIDs['id'];
    }
}
