<?php

namespace CHD\Helpers;

class webRequest
{
    public function get($url)
    {
        echo 'getting page:'.PHP_EOL.$url.PHP_EOL.'...';
        $data = file_get_contents($url);
        echo 'DONE'.PHP_EOL;

        return trim(preg_replace('/\s+/', ' ', $data));
    }
}
