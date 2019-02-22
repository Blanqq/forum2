<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MentionUsersTest extends TestCase
{
    use DatabaseMigrations;
    public function test_user_mentioned_in_a_reply_is_notified_about_it()
    {
        $user = create('App\User', ['name' => 'JohnDoe']);
        $this->signIn($user);

        $userMentioned = create('App\User', ['name' => 'JaneDoe']);

        $thread = create('App\Thread');

        $reply = make('App\Reply', ['body' => '@JaneDoe check this.']);

        $this->json( 'post',$thread->path().'/replies', $reply->toArray());

        $this->assertCount(1, $userMentioned->notifications);
    }
}
