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
        $this->post('/threads/'.$thread->id.'/replies', $reply->toArray());
        
        $this->get('/threads/'.$thread->id)->assertSee($reply->body);
    }
    public function test_test($param) {
        $thread = factory('App\Thread')->create();
    }
}
