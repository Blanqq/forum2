<?php

namespace Tests\Feature;

use Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseMigrations;
    
    public function setUp() 
    {
        parent::setUp();
        $this->thread = factory('App\Thread')->create();//1
    }

    public function test_thread_have_a_path()
    {
        $thread = create('App\Thread');

        $this->assertEquals("/threads/{$thread->channel->slug}/{$thread->slug}",  $thread->path() );
    }

    public function test_a_user_can_read_all_threads()
    {
        //$thread = factory('App\Thread')->create();//1
        
        $response = $this->get('/threads');
        $response->assertSee($this->thread->title);
        
        
    }

    public function test_a_user_can_read_a_single_thread()
    {
        //$thread = factory('App\Thread')->create();//1
        
        $response = $this->get($this->thread->path());
        $response->assertSee($this->thread->title);
    }
    /*public function test_a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        $reply = factory('App\Reply')->create(['thread_id' => $this->thread->id]);
        $response = $this->get($this->thread->path());
        $response->assertSee($reply->body);
    }*/
    public function test_a_user_can_filter_threads_by_channel()
    {
        $channel = create('App\Channel');
        //dd($channel->id);
        $threadInChannel = create('App\Thread', ['channel_id' => $channel->id]);
        $otherChannel = create('App\Channel', ['id' => ++$channel->id]);
        $threadNotInChannel = create('App\Thread', ['channel_id' => $otherChannel->id]);

        $this->get('/threads/'.$channel->slug)
            ->assertSee($threadInChannel->title)
                ->assertDontSee($threadNotInChannel->title);
    }
    public function test_user_can_filter_threads_by_any_username()
    {
        $this->signIn(create('App\User', ['name' => 'JohnDoe']));

        $threadByJohnDoe = create('App\Thread', ['user_id' => auth()->id()]);
        Auth::logout();
        $this->signIn(create('App\User'));


        $otherThread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->get('/threads?by=JohnDoe')
            ->assertSee($threadByJohnDoe->title)
                ->assertDontSee($otherThread->title);
    }
    public function test_a_user_can_filter_threads_by_popularity(){

        $threadWithTwoReplys = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWithTwoReplys->id], 2);

        $threadWithThreeReplys = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWithThreeReplys->id], 3);

        //threadWithZero is created in setUp function on top of this class

        $response = $this->getJson('threads?popularity=1')->json();

        $this->assertEquals([3, 2, 0], array_column($response['data'], 'replies_count'));

    }
    public function test_a_user_can_filter_threads_by_those_that_are_unanswered()
    {
        $thread = create('App\Thread');
        create('App\Reply', ['thread_id' => $thread->id]);

        $response = $this->getJson('threads?unanswered=1')->json();
        $this->assertCount(1, $response['data']);
    }

    public function test_a_user_can_request_all_replies_for_a_thread()
    {
        $thread = create('App\Thread');
        create('App\Reply',['thread_id' => $thread->id], 2);
        $response = $this->getJson($thread->path().'/replies')->json();

        $this->assertCount(2, $response['data']);
        $this->assertEquals(2, $response['total']);
    }

    public function test_records_visits_each_time_a_thread_is_read()
    {
        $thread = create('App\Thread');

        $this->assertSame(0, $thread->visits_count);

        $this->call('GET', $thread->path());

        $this->assertEquals(1, $thread->fresh()->visits_count);

    }
}
