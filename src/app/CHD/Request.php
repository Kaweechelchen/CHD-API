<?php

namespace App\CHD;

class Request
{
    protected $data;

    public function get($url)
    {
        if (isset($this->data[$url])) {
            return $this->data[$url];
        }

        //$this->app['log']->debug('getting page:', array('url' => $url));
        //echo 'getting page:'.$url;
        $data = file_get_contents($url);
        //$this->app['log']->info('loaded page: ', array('url' => $url));
        //echo 'loaded page: '.$url;

        $this->data[$url] = trim(preg_replace('/\s+/', ' ', $data));

        return $this->data[$url];
    }
}
