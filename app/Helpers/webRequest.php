<?php

namespace CHD\Helpers;

class webRequest
{
    protected $app;
    public function __construct($app)
    {
        $this->app = $app;
    }

    public function get($url)
    {
        $this->app['log']->debug('getting page:', array('url' => $url));
        $data = file_get_contents($url);
        $this->app['log']->info('loaded page: ', array('url' => $url));

        return trim(preg_replace('/\s+/', ' ', $data));
    }
}
