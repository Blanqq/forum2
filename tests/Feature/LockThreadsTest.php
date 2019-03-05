<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LockThreadsTest extends TestCase
{
    use DatabaseMigrations;
    public function test_administratorMayLockTheThread()
    {
        $this->signIn();
        $thread = create('App\Thread');
        $thread->lock();

        $this->post($thread->path().'/replies', [
            'body' => 'Some Body',
            'user_id' => auth()->id()
        ])->assertStatus(422);
    }
}
