<?php

namespace Tests\Unit;

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

    public function test_a_thread_can_make_string_path(){
        $thread = create('App\Thread'); //make thread
        //$this->assertEquals('/threads/' .$thread->channel->slug. '/'. $thread->id, $thread->path());
        $this->assertEquals("/threads/{$thread->channel->slug}/{$thread->id}", $thread->path());
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
}