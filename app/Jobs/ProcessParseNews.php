<?php

namespace App\Jobs;

use App\ParserModule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class ProcessParseNews implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $parser;
    protected $delay_time;
    protected $key_lock;

    /*
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($key, $delay)
    {
        $this->parser = new ParserModule();
        $this->delay_time = $delay;
        $this->key_lock = $key;
    }

    /**
     * Load content on db.
     *
     * @content array
     *
     * @return void
     */
    public function handle()
    {
        $lock = Cache::store('database')->lock($this->key_lock);

        if ($lock->get()) {
            $lock->forceRelease();
        } else {
            $this->parser->loadContent();
            dispatch((new ProcessParseNews($this->key_lock, $this->delay_time))->delay($this->delay_time));
        }
    }
}
