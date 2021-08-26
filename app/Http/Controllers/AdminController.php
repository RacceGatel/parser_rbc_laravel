<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminController extends Controller
{

    public function index() {

        $logs = Log::paginate(5);

        $key = Cache::store('database')->get('parsing_key_lock');

        $key ? $status = true : $status = false;

        return view('admin', compact('logs', 'status'));
    }
}
