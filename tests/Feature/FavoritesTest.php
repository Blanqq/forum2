<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavoritesTest extends TestCase
{
    use DatabaseMigrations;

    public function test_guest_cant_favorite_any_reply(){
        $this->withExceptionHandling()->post('replies/1/favorites')
            ->assertRedirect('/login');
    }

   public function test_authenticated_user_can_favorite_any_reply(){

        $this->signIn();
        $reply = create('App\Reply');
        $this->post('replies/'. $reply->id.'/favorites');

        $this->assertCount(1, $reply->favorites);

    }
    public function test_authenticated_user_can_favorite_any_reply_only_once(){

        $this->signIn();
        $reply = create('App\Reply');
        try{
            $this->post('replies/'. $reply->id.'/favorites');
            $this->post('replies/'. $reply->id.'/favorites');
        } catch (\Exception $e){
            $this->fail('Cant favorite same record twice');
        }
        $this->assertCount(1, $reply->favorites);

    }
}