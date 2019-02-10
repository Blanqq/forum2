<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class NotificationTest extends TestCase
{
    use DatabaseMigrations;
    public function test_a_notification_is_prepared_when_reply_is_added_to_subscribed_thread_not_created_by_current_user()
    {
        $this->signIn();
        $thread = create('App\Thread')->subscribe();
        $this->assertCount(0, auth()->user()->notifications);

        $thread->addReply([
            'user_id' => auth()->id(),
                'body' => 'Some text for the reply'
        ]);
        $this->assertCount(0, auth()->user()->fresh()->notifications);

        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'Some text for the reply'
        ]);
        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }
}
