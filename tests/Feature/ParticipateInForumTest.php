<?php

namespace Tests\Feature;

use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInForum extends TestCase
{
   
    use DatabaseMigrations;
    
    public function test_authenticated_user_can_participate_in_forum_threads()
    {
        //$user = factory('App\User')->create();
        //$this->be($user);          //sets currently loggedin user to the aplication
        
        $this->be($user = factory('App\User')->create());  //można tak w 1 lini
        
        $thread = factory('App\Thread')->create();
        
        //$reply = factory('App\Reply')->create();
        $reply = factory('App\Reply')->create(['thread_id' => $thread->id]);
        $this->post($thread->path(). '/replies', $reply->toArray());
        
        $this->get($thread->path())
            ->assertSee($reply->body);
    }
    public function test_a_reply_requires_a_body()
    {
        $this->withExceptionHandling()->signIn();
        $thread = create('App\Thread');
        $reply = make('App\Reply', ['body' => null]);

        $this->post($thread->path().'/replies', $reply->toArray())->assertSessionHasErrors('body');
    }
}
