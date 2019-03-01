<?php

namespace App;

use App\Thread;
use Illuminate\Support\Facades\Redis;

class Trending
{
    public function get()
    {
        return array_map('json_decode', Redis::zrevrange($this->cacheKey(), 0, 5));
    }

    public function increment(Thread $thread)
    {
        Redis::zincrby($this->cacheKey(), 1, json_encode([
            'title' => $thread->title,
            'path' => $thread->path()
        ]));
    }

    public function cacheKey()
    {
        if(app()->environment('testing'))
        {
            return 'testing_trending_threads';
        }

        return 'trending_threads';
    }

    public function reset()
    {
        Redis::del($this->cacheKey());
    }
}