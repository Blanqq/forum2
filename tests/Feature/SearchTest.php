<?php

namespace Tests\Feature;

use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_search_threads()
    {
        config(['scout.driver' => 'algolia']);
        $search = 'foobar';
        create('App\Thread', [], 2);
        create('App\Thread', ['body' => "A thread with the {$search} keyword"], 2);
        do{ // because of network delay
            sleep(2);
            $results = $this->getJson("/threads/search?q={$search}")->json();
        }while(empty($results));


        $this->assertCount(2, $results['data']);

        Thread::latest()->take(4)->unsearchable();
    }
}
