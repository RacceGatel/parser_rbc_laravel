<?php

namespace App\Console\Commands;

use App\Http\Controllers\ParsenewsController;
use App\Jobs\ProcessParseNews;
use App\ParserModule;
use Illuminate\Console\Command;

class startRbcParse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:rbc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to parse info from rbc news';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     *
     */
    public function handle()
    {
        //dispatch((new ProcessParseNews())->onQueue('parsing'));

        $cont = (new ParserModule())->getContent();


        return 'started';
    }

}
