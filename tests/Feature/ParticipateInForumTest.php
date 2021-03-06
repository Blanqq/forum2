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
        $reply = factory('App\Reply')->make(['thread_id' => $thread->id]);

        //dd($thread->fresh()->replies_count);
        $this->post($thread->path(). '/replies', $reply->toArray());


        
        /*$this->get($thread->path())
            ->assertSee($reply->body);*/
        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
        //dd($thread->fresh()->replies);
        $this->assertEquals(1, $thread->fresh()->replies_count);
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
            ->assertRedirect('login');

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

        $this->assertEquals(0, $reply->thread->fresh()->replies_count);
    }
    public function  test_authorized_users_can_update_replies()
    {
        $this->signIn();
        $reply = factory(Reply::class)->create(['user_id' => auth()->id()]);
        $updatedBody = 'Body was edited';
        $this->patch("/replies/{$reply->id}", ['body' => $updatedBody]);

        $this->assertDatabaseHas('replies', ['id' => $reply->id,
            'body' => $updatedBody
            ]);
    }
    public function test_unauthorized_users_cannot_update_replies()
    {
        $this->withExceptionHandling();
        $reply = factory(Reply::class)->create();

        $this->patch("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->signIn()
            ->patch("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    public function test_replies_that_contain_spam_may_not_be_created()
    {
        $this->withExceptionHandling();
        $this->signIn();
        $thread = create('App\Thread');
        $reply = make('App\Reply', ['body' => 'Yahoo Customer Support']);

        $this->json( 'post',$thread->path().'/replies', $reply->toArray())->assertStatus(422);

    }

    public function test_users_may_post_once_per_minute()
    {
        $this->withExceptionHandling();
        $this->signIn();
        $thread = create('App\Thread');

        $reply = make('App\Reply', [
            'body' => 'Some body'
        ]);
        //dd($reply);
        $this->post($thread->path().'/replies', $reply->toArray())
            ->assertStatus(200);

        $this->post($thread->path().'/replies', $reply->toArray())
            ->assertStatus(429);
    }
}
