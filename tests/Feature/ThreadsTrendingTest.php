<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Redis;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadsTrendingTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        Redis::del('trending_threads');
    }
    public function test_it_increases_threads_score_evrytime_it_is_read()
    {
        $this->assertCount(0, Redis::zrevrange('trending_threads', 0, -1)); //   ||assertEmpty
        $thread = create('App\Thread');
        $this->call('GET', $thread->path());

        $this->assertCount(1, Redis::zrevrange('trending_threads', 0, -1));

    }
}
