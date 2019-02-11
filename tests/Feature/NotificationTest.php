<?php

namespace Tests\Feature;

use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class NotificationTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->signIn();
    }
    public function test_a_notification_is_prepared_when_reply_is_added_to_subscribed_thread_not_created_by_current_user()
    {
        $thread = create('App\Thread')->subscribe();
        $this->assertCount(0, auth()->user()->notifications);   // notifications and unreadNotifications are functions of notification trait

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

    public function test_a_user_can_fetch_his_unread_notifications()
    {
        create(DatabaseNotification::class);

        $response = $this->getJson('/profiles/'.auth()->user()->name.'/notifications')->json();

        $this->assertCount(1, $response);
    }
    public function test_user_can_mark_notifications_as_read()
    {


        create(DatabaseNotification::class);
        $this->assertCount(1, auth()->user()->unreadNotifications);

        $notification_id = auth()->user()->unreadNotifications->first()->id;

        $this->delete('/profiles/'.auth()->user()->name.'/notifications/'.$notification_id);

        $this->assertCount(1, auth()->user()->unreadNotifications);
    }
}
