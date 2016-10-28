<?php

namespace CHD\Helpers;

class CHDPetition
{
    protected $app;
    public function __construct($app)
    {
        $this->app = $app;
    }

    public function get($petition)
    {
        $petition['daniel'] = 'test';

        return $this->app['webRequest']->get($this->app['CHD']['petition']['url'].$petition['id']);

        return $petition;
    }
}
