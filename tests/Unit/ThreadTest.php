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
    
    public function test_a_thread_has_replies() 
    {
        $thread = factory('App\Thread')->create();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $thread->replies);
    }
}