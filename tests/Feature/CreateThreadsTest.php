<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    use DatabaseMigrations;
    public function test_an_authenticated_user_can_create_new_threads()
    {
        $this->signIn();
        //$this->be($user = factory('App\User')->create());
        //user loged in
        
        $thread = create('App\Thread');   // used function create from utilities/functions.php
        //$thread = factory('App\Thread')->create();
        //create thread
        
        $this->post('/threads', $thread->toArray());
        //visit thread page
        
        $this->get('/threads')->assertSee($thread->title);
        //$this->assertSee($thread->title);
        
        
                
        //check if thread exists
    }
    
    public function test_guest_cant_see_create_thread_page()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->get('threads/create')->assertRedirect('/login');//->assertSee('Create a New Thread')
        
    }


    public function test_gueast_can_not_create_thread() 
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        
        $thread = create('App\Thread'); //used function from utilities/function.php if not use syntax from below 
        //$thread = factory('App\Thread')->create();
        
        
        $this->post('/threads', $thread->toArray());
    }
}
