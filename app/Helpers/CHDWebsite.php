<?php

namespace CHD\Helpers;

class CHDWebsite
{
    public function getScrapePath()
    {
        $data                  = $app['webRequest']->get(__DIR__.'/../../../old/source/petitions.html');
        $scrapePathPattern     = '/action="(?P<scrapePath>[^#]*=(?P<type>.[^\/]*)\/-\/)/';

        if (!preg_match($scrapePathPattern, $data, $scrapePath)) {
            throw new Exception('couldn\'t find the scrape Path');
        }

        $scrapePath = str_replace($scrapePath['type'], '%s', $scrapePath['scrapePath']);

        return $app['CHD']['host'].$scrapePath.'%s';
    }
}
