<?php

namespace Tests\Feature;

use App\Rules\Recaptcha;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class CreateThreadsTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        // for every request recaptcha will pass true
        app()->singleton(Recaptcha::class, function () {
            return \Mockery::mock(Recaptcha::class, function ($m) {
                $m->shouldReceive('passes')->andReturn(true);
            });
        });
    }
    public function test_an_authenticated_user_can_create_new_threads()
    {
        $this->signIn();
        
        $thread = make('App\Thread');

        $response = $this->post(route('threads'), $thread->toArray()+ ['g-recaptcha-response' => 'token']);

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

    public function test_a_thread_requires_recaptcha_validation()
    {
        unset(app()[Recaptcha::class]);
        $this->signIn()->withExceptionHandling();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $this->post('/threads', $thread->toArray()+ ['g-recaptcha-response' => 'token'])
            ->assertSessionHasErrors('g-recaptcha-response');
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
        create('App\Thread', [], 2);
        $thread = create('App\Thread', ['title' => 'Some Title']);
        $this->assertEquals($thread->fresh()->slug, 'some-title');
        $thread2 = $this->postJson(route('threads'), $thread->toArray()+ ['g-recaptcha-response' => 'token'])
            ->json();
        $this->assertEquals("some-title-{$thread2['id']}", $thread2['slug']);
/*        $this->post(route('threads'), $thread->toArray());
        $this->assertTrue(Thread::whereSlug('some-title-3')->exists());*/
    }

    public function test_thread_with_digits_at_the_end_should_generate_proper_slug()
    {
        //['g-recaptcha-response' => 'token']
        $this->signIn();
        $thread = create('App\Thread', ['title' => 'Some Title 55']);
        $thread = $this->postJson(route('threads'), $thread->toArray()+['g-recaptcha-response' => 'token'])->json();
        $this->assertEquals("some-title-55-{$thread['id']}", $thread['slug']);

    }

}
