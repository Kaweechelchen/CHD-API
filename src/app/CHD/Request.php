<?php

namespace App\CHD;

use Illuminate\Support\Facades\Log;

class Request
{
    protected $data;

    public function get($url)
    {
        if (isset($this->data[$url])) {
            return $this->data[$url];
        }

        Log::info('request started for:  '.$url);

        $data = file_get_contents($url);
        //$this->app['log']->info('loaded page: ', array('url' => $url));

        Log::info('request finished for: '.$url);

        $this->data[$url] = trim(preg_replace('/\s+/', ' ', $data));

        return $this->data[$url];
    }
}
