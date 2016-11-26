<?php

namespace App\CHD;

use Exception;

class PetitionPages
{
    protected $pages;

    public function init()
    {
        $data = app('Request')->get(env('CHD_HOST').env('CHD_LIST'));

        $lastPagePattern = '/for="pageNumber"[^\/]*\/\s*(?P<lastPage>\d+)/';
        if (!preg_match($lastPagePattern, $data, $lastPage)) {
            throw new Exception('couldn\'t find last page');
        }
        $lastPage    = $lastPage['lastPage'];
        $this->pages = range(1, $lastPage);
    }

    public function get()
    {
        return $this->pages;
    }
}
