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
        
        $thread = make('App\Thread');   // used function create from utilities/functions.php
        //$thread = factory('App\Thread')->create();
        //create thread
        
        $response = $this->post('/threads', $thread->toArray());
        //visit thread page
        //dd($thread->path());

        //dd($response->headers->get('Location'));

        $this->get($response->headers->get('Location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
        //$this->assertSee($thread->title);
        
        
                
        //check if thread exists
    }
    
    public function test_guest_cant_see_create_thread_page()
    {
        $this->withExceptionHandling();
        $this->get('/threads/create')->assertRedirect('login');
        $this->post('/threads')->assertRedirect('login');
    }
    function test_a_thread_requires_a_title(){
        $this->withExceptionHandling()->signIn();
        $thread = make('App\Thread', ['title' => null]);
        $this->post('/threads', $thread->toArray())->assertSessionHasErrors('title');
    }
    function test_a_thread_requires_a_body(){
        $this->withExceptionHandling()->signIn();
        $thread = make('App\Thread', ['body' => null]);
        $this->post('/threads', $thread->toArray())->assertSessionHasErrors('body');
    }
    function test_a_thread_requires_a_proper_channel_id(){
        $this->withExceptionHandling()->signIn();
        factory('App\Channel', 2)->create();
        $thread = make('App\Thread', ['channel_id' => null]);
        $this->post('/threads', $thread->toArray())->assertSessionHasErrors('channel_id');
    }
}
