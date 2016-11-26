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

        $data = substr(
            $data,
            strpos($data, '<!-- END petitionElementsList -->')
        );

        $urlPattern = '/action="(?P<path>[^#]*=(?P<type>.[^\/]*)\/-\/)/';
        if (!preg_match($urlPattern, $data, $url)) {
            throw new Exception('couldn\'t find the scrape Path');
        }

        $this->path = env('CHD_HOST')
            .str_replace($url['type'], '%s', $url['path'])
            .'%s';

        $this->path = 'http://chd.lu/wps/portal/public/!ut/p/b1/jctLDoIwFIXhtbiCnl5sgeFF6SNEgzYS6cQwIIaEx8S4fnEBRs7sJP8nomgTkCSlUoi7iHP3Hp7da1jmbvz-qB8E0zSGLBdWK7ANukprD8vpGrRrIL1i50EORmv42h1zXe2TysptnrIsXC2fyjy_FfA4yFBeCoKnbR4_xvjnQz-Ls1umXkxxNCbj3QdiCTZM/dl4/d5/L2dBISEvZ0FBIS9nQSEh/pw/Z7_5T6UAKA71GR720A872MA2Q1GS5/ren/p=ePetition=%s/-/%s';
    }
}
