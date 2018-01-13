<?php

namespace Tests\Feature;

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
    public function test_a_user_can_read_repilies_that_are_associated_with_a_thread() 
    {
        $reply = factory('App\Reply')->create(['thread_id' => $this->thread->id]);
        $response = $this->get($this->thread->path());
        $response->assertSee($reply->body);
    }
    public function test_a_user_can_filter_threads_by_channel()
    {
        $channel = create('App\Channel');
        //dd($channel->id);
        $threadInChannel = create('App\Thread', ['channel_id' => $channel->id]);
        $threadNotInChannel = create('App\Thread');
        //dd($threadInChannel->title."aa".$threadNotInChannel->title);

        $this->get('/threads/'.$channel->slug)
            ->assertSee($threadInChannel->title)
                ->assertDontSee($threadNotInChannel->title);
    }
    public function test_user_can_filter_threads_by_any_username()
    {
        $this->signIn(create('App\User', ['name' => 'JohnDoe']));

        $threadByJohnDoe = create('App\Thread', ['user_id' => auth()->id()]);
        $otherThread = create('App\Thread');

        $this->get('/threads?by=JohnDoe')
            ->assertSee($threadByJohnDoe->title)
                ->assertDontSee($otherThread->title);
    }
    public function test_a_user_can_filterthreads_by_popularity(){

        $threadWithTwoReplys = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWithTwoReplys->id], 2);

        $threadWithThreeReplys = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWithThreeReplys->id], 3);

        //threadWithZero is created in setUp function on top of this class

        $response = $this->getJson('threads?popularity=1')->json();

        $this->assertEquals([3, 2, 0], array_column($response, 'replies_count'));

    }
}
