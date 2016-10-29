<?php

use Phinx\Seed\AbstractSeed;

class Init extends AbstractSeed
{
    public function run()
    {
        $this->execute(file_get_contents(__DIR__.'/init.sql'));
    }
}
