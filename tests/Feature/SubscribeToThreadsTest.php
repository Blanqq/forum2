<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SubscribeToThreadsTest extends TestCase
{
    use DatabaseMigrations;

    public function test_a_user_can_subscribe_to_threads()
    {
        $this->signIn();
        $thread = create('App\Thread');
        $this->post($thread->path().'/subscriptions');

        /*$thread->addReply([
           'user_id' => auth()->id(),
           'body' => 'Some text for the reply'
        ]);*/
        $this->assertCount(1, $thread->subscriptions);

    }
    public function test_a_user_can_unsubscribe_to_threads()
    {
        $this->signIn();
        $thread = create('App\Thread');
        $this->post($thread->path().'/subscriptions');
        $this->assertCount(1, $thread->subscriptions);

        $this->delete($thread->path().'/subscriptions');
        $this->assertCount(0, $thread->fresh()->subscriptions);
    }
}
