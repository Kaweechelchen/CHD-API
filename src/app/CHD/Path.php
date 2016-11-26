<?php

namespace App\CHD;

use Exception;

class Path
{
    protected $path;

    /**
     * Returns path prefilled with the correct type and arguments.
     * @param  string $type      Type of request (listPetitionRole,...)
     * @param  string $arguments Query parameters (?type=TOUTES&etat=TOUS...)
     * @return string prefilled URL
     */
    public function get($type, $arguments)
    {
        if (!$this->path) {
            return false;
        }

        return sprintf($this->path, $type, $arguments);
    }

    /**
     * Returns the URL of the first petitions page.
     * @return string URL of the first petitions page
     */
    public function init()
    {
        $data = app('Request')->get(env('CHD_HOST').env('CHD_LIST'));

        $urlPattern = '/action="(?P<path>[^#]*=(?P<type>.[^\/]*)\/-\/)/';
        if (!preg_match($urlPattern, $data, $url)) {
            throw new Exception('couldn\'t find the scrape Path');
        }

        $this->path = env('CHD_HOST')
            .str_replace($url['type'], '%s', $url['path'])
            .'%s';
    }
}
