<?php

namespace CHD\Helpers;

class CHDPetitionPages
{
    protected $app;
    protected $pages;

    public function __construct($app)
    {
        $this->app = $app;
    }
    public function get()
    {
        $lastPagePattern = '/for="pageNumber"[^\/]*\/\s*(?P<lastPage>\d+)/';
        if (!preg_match($lastPagePattern, $this->app['CHDFirstPage'], $lastPage)) {
            throw new Exception('couldn\'t find last page');
        }
        $lastPage = $lastPage['lastPage'];

        return range(1, $lastPage);
    }
}
