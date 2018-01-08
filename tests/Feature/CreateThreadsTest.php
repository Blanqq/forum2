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
    
    /*public function test_guest_cant_see_create_thread_page()
    {
        $this->withExceptionHandling();   //exceptionHandling dont ned to fix "unauthenticated"
        $this->get('/threads/create')->assertRedirect('/login');
        $this->post('/threads')->assertRedirect('/login');
    }*/
    /*function test_a_thread_requires_a_title(){
        $this->withExceptionHandling()->signIn();
        //$this->withExceptionHandling();
        //$this->signIn();
        $thread = make('App\Thread', ['title' => null]);

        //dd($thread);
        //dd($thread->toArray());
        $this->post('/threads', $thread->toArray())
            ->assertSessionHasErrors('title');
    }*/
}
