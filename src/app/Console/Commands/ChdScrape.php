<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ScrapeController;

class ChdScrape extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chd:scrape';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrapes the petition pages from chd.lu';

    protected $scraper;

    /**
     * Create a new command instance.
     */
    public function __construct(ScrapeController $scraper)
    {
        parent::__construct();

        $this->scraper = $scraper;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->scraper->index();
    }
}
