<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Trending;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function show()
    {
        $threads = Thread::all();
        if(request()->expectsJson())
        {
            return $threads;
        }
        return view('threads.search', [
            'threads' => $threads
        ]);
    }
}
