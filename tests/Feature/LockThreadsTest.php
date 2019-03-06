<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LockThreadsTest extends TestCase
{
    use DatabaseMigrations;
    public function test_thread_can_be_locked()
    {
        $this->signIn();
        $thread = create('App\Thread');
        $thread->close();

        $this->post($thread->path().'/replies', [
            'body' => 'Some Body',
            'user_id' => auth()->id()
        ])->assertStatus(422);
    }

    public function test_non_administrator_cant_lock_thread()
    {
        $this->signIn()->withExceptionHandling();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->post(route('locked-threads.store', $thread))->assertStatus(403);

        $this->assertFalse($thread->fresh()->locked);
    }

    public function test_administrator_can_lock_thread()
    {
        $this->signIn(factory('App\User')->states('administrator')->create());
        $thread = create('App\Thread');
        $this->post(route('locked-threads.store', $thread))->assertStatus(200);
        $this->assertTrue($thread->fresh()->locked);

    }

    public function test_administrator_can_unlock_thread()
    {
        $this->signIn(factory('App\User')->states('administrator')->create());
        $thread = create('App\Thread', ['locked' => true]);
        $this->delete(route('locked-threads.destroy', $thread))->assertStatus(200);
        $this->assertFalse($thread->fresh()->locked);

    }
}
