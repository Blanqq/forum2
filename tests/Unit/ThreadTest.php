<?php

namespace Tests\Unit;

use App\Notifications\ThreadWasUpdated;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use \Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadTest extends TestCase
{
    //protected $thread;

    use DatabaseMigrations;
    public function test_a_thread_has_an_author()
    {
        $thread = factory('App\Thread')->create();
        
        $this->assertInstanceOf('App\User', $thread->author);
    }
    public function test_a_thread_can_add_a_reply() 
    {
        $thread = factory('App\Thread')->create();
        
        $thread->addReply([
           'body' => 'Foobar',
            'user_id' => 1
        ]);
        
        $this->assertCount(1, $thread->replies);
    }

    public function test_thread_notifies_allregistered_subscribers_reply_was_added()
    {
        Notification::fake();

        $this->signIn();
        $thread = factory('App\Thread')->create();
        $thread->subscribe();
        $thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1000
        ]);
        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
    }

    public function test_a_thread_can_make_string_path(){
        $thread = create('App\Thread'); //make thread
        //$this->assertEquals('/threads/' .$thread->channel->slug. '/'. $thread->id, $thread->path());
        $this->assertEquals("/threads/{$thread->channel->slug}/{$thread->slug}", $thread->path());
    }
    
    public function test_a_thread_has_replies() 
    {
        $thread = factory('App\Thread')->create();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $thread->replies);
    }
    /** @test */
    public function thread_belongs_to_a_channel()
    {
        $thread = create('App\Thread');   // make a thread
        $this->assertInstanceOf('App\Channel', $thread->channel);  //check
    }

    public function test_a_thread_can_be_subscribed_to()
    {
        $thread = create('App\Thread');
        $this->signIn();
        $thread->subscribe();
        $this->assertEquals(1, $thread->subscriptions()->where('user_id', auth()->id())->count());
    }
    public function test_athread_can_be_unsubscribed_from()
    {
        $thread = create('App\Thread');
        $this->signIn();
        $thread->subscribe();
        $thread->unsubscribe();
        $this->assertEquals(0, $thread->subscriptions()->where('user_id', auth()->id())->count());
    }
    public function test_it_knows_if_the_authenticated_user_is_subscribed_to_it()
    {
        $thread = create('App\Thread');
        $this->signIn();

        $this->assertFalse($thread->isSubscribedTo);

        $thread->subscribe();

        $this->assertTrue($thread->isSubscribedTo);
    }

    public function test_check_if_authenticated_user_has_read_all_replies()
    {
        $this->signIn();
        $thread = create('App\Thread');
        $this->assertTrue($thread->hasUpdatesFor(auth()->user()));

        //$key = sprintf("users.%s.visits.%s", auth()->id(), $thread->id);
        cache()->forever(auth()->user()->visitedThreadCacheKey($thread), \Carbon\Carbon::now());

        $this->assertFalse($thread->hasUpdatesFor(auth()->user()));
    }

    public function test_threadCanBeLocked()
    {
        $thread = create('App\Thread');
        $this->assertFalse($thread->locked);
        $thread->close();
        $this->assertTrue($thread->locked);
    }

    public function  test_a_thread_body_is_sanitized_automatically()
    {
        $thread = make('App\Thread', ['body' => '<script>alert("something malicious")</script><p>This can pass</p>']);
        $this->assertEquals('<p>This can pass</p>', $thread->body);
    }

}