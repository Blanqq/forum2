<?php

namespace Tests\Feature;

use App\Reply;
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
    public function test_unauthorized_users_cannot_delete_replies()
    {
        $this->withExceptionHandling();
        $reply = factory(Reply::class)->create();

        $this->delete("/replies/{$reply->id}")
            ->assertStatus(403);

        $this->signIn()
            ->delete("/replies/{$reply->id}")
            ->assertStatus(403);
    }
    public function test_authorized_users_can_delete_replies()
    {
        $this->signIn();
        $reply = factory(Reply::class)->create(['user_id' => auth()->id()]);
        $this->delete("/replies/{$reply->id}");

        $this->assertDatabaseMissing('replies', ['id' => $reply->id,
            'body' => $reply->body
            ]);
    }
}
