<?php

namespace App\CHD;

use Illuminate\Support\Facades\Log;
use ErrorException;
use Exception;

class Request
{
    protected $data;

    public function get($url)
    {
        if (isset($this->data[$url])) {
            return $this->data[$url];
        }

        Log::info('request started for:  '.$url);

        $fetched = false;
        $retry   = 1;

        while ($retry <= 5 && !$fetched) {
            $data = $this->fetch($url);
            if ($data) {
                $fetched = true;
            } else {
                sleep(10);
                Log::error('retry '.$retry.' for: '.$url);
                ++$retry;
            }
        }

        if (!$fetched) {
            throw new Exception('couldn\'t fetch: '.$url);
        }

        Log::info('request finished for: '.$url);

        $this->data[$url] = trim(preg_replace('/\s+/', ' ', $data));

        return $this->data[$url];
    }

    protected function fetch($url)
    {
        try {
            return file_get_contents($url);
        } catch (ErrorException $e) {
            return false;
        }
    }
}
