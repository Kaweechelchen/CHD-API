<?php

namespace CHD\Helpers;

use Exception;

class CHDScrapePath
{
    protected $url;

    public function __construct($app)
    {
        $urlPattern = '/action="(?P<path>[^#]*=(?P<type>.[^\/]*)\/-\/)/';

        if (!preg_match($urlPattern, $app['CHDFirstPage'], $url)) {
            throw new Exception('couldn\'t find the scrape Path');
        }

        $this->url = $app['CHD']['host']
                    .str_replace($url['type'], '%s', $url['path'])
                    .'%s';
    }

    public function get($type, $arguments)
    {
        return sprintf($this->url, $type, $arguments);
    }
}
