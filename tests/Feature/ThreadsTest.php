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
}
