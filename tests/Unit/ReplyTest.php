<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReplyTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseMigrations;
    public function test_it_has_an_owner() 
    {
        $reply = factory('App\Reply')->create();
        
        $this->assertInstanceOf('App\User', $reply->owner);
    }

    public function test_it_knows_it_was_just_published()
    {
        $reply = factory('App\Reply')->create();
        $this->assertTrue($reply->wasJustPublished());

    }
    public function test_it_can_detect_mentioned_users()
    {
        $reply = create('App\Reply', [
            'body' => '@JaneDoe has something to say to @JohnDoe'
        ]);

        $this->assertEquals(['JaneDoe', 'JohnDoe'], $reply->mentionedUsers());
    }

    public function test_it_wraps_mentioned_usernames_in_the_reply_body_within_anchor_tags()
    {
        /*$reply = new \App\Reply ([    // without using factory should bee faster
            'body' => 'Hello mr @John-Doe.'
        ]);*/
        $reply = create('App\Reply', [
            'body' => 'Hello mr @John-Doe.'
        ]);

        $this->assertEquals('Hello mr <a href="/profiles/John-Doe">@John-Doe</a>.', $reply->body);
    }

    public function test_it_knows_if_it_is_best()
    {
        $reply = create('App\Reply');
        $this->assertFalse($reply->isBest());
        $reply->thread->update(['best_reply_id' => $reply->id]);
        $this->assertTrue($reply->fresh()->isBest());
    }
}
