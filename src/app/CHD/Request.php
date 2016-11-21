<?php

namespace App\CHD;

class Request
{
    public function get($url)
    {
        //$this->app['log']->debug('getting page:', array('url' => $url));
        //echo 'getting page:'.$url;
        $data = file_get_contents($url);
        //$this->app['log']->info('loaded page: ', array('url' => $url));
        //echo 'loaded page: '.$url;

        return trim(preg_replace('/\s+/', ' ', $data));
    }
}
