<?php

namespace Tests\Feature;

use App\Thread;
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
        
        $thread = make('App\Thread');

        $response = $this->post(route('threads'), $thread->toArray());

        $this->get($response->headers->get('Location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    public function test_authenticated_user_must_confirm_email_address_before_creating_threads()
    {
        $user = factory('App\User')->states('unconfirmed')->create();

        $this->signIn($user);
        $thread = make('App\Thread');
        $this->post('/threads', $thread->toArray())
            ->assertRedirect(route('threads'))
            ->assertSessionHas('flash', 'You must confirm your email address first');

    }
    
    public function test_guest_cant_see_create_thread_page()
    {
        $this->withExceptionHandling();
        $this->get('/threads/create')->assertRedirect('login');
        $this->post(route('threads'))->assertRedirect('login');
    }
    function test_a_thread_requires_a_title(){
        $this->withExceptionHandling()->signIn();
        $thread = make('App\Thread', ['title' => null]);
        $this->post(route('threads'), $thread->toArray())->assertSessionHasErrors('title');
    }
    function test_a_thread_requires_a_body(){
        $this->withExceptionHandling()->signIn();
        $thread = make('App\Thread', ['body' => null]);
        $this->post(route('threads'), $thread->toArray())->assertSessionHasErrors('body');
    }
    function test_a_thread_requires_a_proper_channel_id(){
        $this->withExceptionHandling()->signIn();
        factory('App\Channel', 2)->create();
        $thread = make('App\Thread', ['channel_id' => null]);
        $this->post(route('threads'), $thread->toArray())->assertSessionHasErrors('channel_id');
    }
    function test_authorized_user_can_delete_threads(){
        $this->signIn();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());
        $response->assertStatus(204); // 200 - OK  204 - NoContent

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertDatabaseMissing('activities', [
            'subject_id' => $thread->id,
            'subject_type' => get_class($thread)
            ]);
        $this->assertDatabaseMissing('activities', [
            'subject_id' => $reply->id,
            'subject_type' => get_class($reply)
        ]);
    }
    function test_unauthorized_user_cant_delete_threads(){
        $this->withExceptionHandling();
        $thread = create('App\Thread');
        $response = $this->delete($thread->path());
        $response->assertRedirect('/login');

        $this->signIn();
        $this->delete($thread->path())
            ->assertStatus(403);
    }

    public function test_a_thread_requires_unique_slug()
    {
        $this->signIn();
        $thread = create('App\Thread', ['title' => 'Some Title', 'slug' => 'some-title']);
        $thread2 = create('App\Thread', ['title' => 'Some Title']);
        $this->assertEquals($thread->fresh()->slug, 'some-title');
        $this->post(route('threads'), $thread2->toArray());
        $this->assertTrue(Thread::whereSlug('some-title-2')->exists());
        $this->post(route('threads'), $thread2->toArray());
        $this->assertTrue(Thread::whereSlug('some-title-3')->exists());

    }

}
