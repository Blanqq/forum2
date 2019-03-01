<?php

namespace Tests\Feature;

use App\Trending;
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
        $this->trending = new Trending();
        $this->trending->reset();
    }
    public function test_it_increases_threads_score_every_time_it_is_read()
    {
        $this->assertCount(0, $this->trending->get()); //   ||assertEmpty
        $thread = create('App\Thread');
        $this->call('GET', $thread->path());

        $this->assertCount(1, $this->trending->get());

    }
}
