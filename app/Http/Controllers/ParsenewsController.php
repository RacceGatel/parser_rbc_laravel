<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessParseNews;
use App\Models\Log;
use App\Models\News;
use App\ParserModule;
use Illuminate\Http\Request;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;

class ParsenewsController extends Controller
{
    protected $delay_time = 15;


    public function start(Request $request)
    {

        $key = Cache::store('database')->get('parsing_key_lock');

        if($key == null) {
            Cache::store('database')->forever('parsing_key_lock', 'parsing_' . time());

            $key = Cache::store('database')->get('parsing_key_lock');

            $lock = Cache::store('database')->lock($key);

            if ($lock->get()) {
                $newKey = Cache::forever('parsing_key_lock', 'parsing_'.time());
                dispatch((new ProcessParseNews($key, $this->delay_time)));
            }
        }

        return redirect()->back();
    }

    public function restart()
    {

        $key = Cache::store('database')->get('parsing_key_lock');

        if($key != null) {
            Cache::store('database')->lock($key)->forceRelease();

            Cache::store('database')->forever('parsing_key_lock', 'parsing_' . time());

            $key = Cache::store('database')->get('parsing_key_lock');

            $lock = Cache::store('database')->lock($key);

            if ($lock->get()) {
                dispatch((new ProcessParseNews($key, $this->delay_time)));
            }
        }

        return redirect()->back();
    }

    public function stop()
    {
        $key = Cache::store('database')->get('parsing_key_lock');

        if($key != null) {

            Cache::store('database')->lock($key)->forceRelease();

            Cache::store('database')->delete('parsing_key_lock');
        }

        return redirect()->back();
    }
}
