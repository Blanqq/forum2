<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;

class LockedThreadController extends Controller
{
    public function store(Thread $thread)
    {   // authorization through middleware
        $thread->close();   // using names close/open because lock/unlock methods exists in larvel query builder and will be overwritten
    }

    public function destroy(Thread $thread)
    {
        $thread->open();
    }
}
