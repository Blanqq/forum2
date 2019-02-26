<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Storage;

class AvatarTest extends TestCase
{
    use DatabaseMigrations;
    public function test_only_registered_user_can_have_avatar()
    {
        $this->withExceptionHandling();
        $response = $this->json('POST', '/api/users/1/avatar');
        $response->assertStatus(401);
    }
    public function test_a_valid_avatar_must_be_provided()
    {
        $this->withExceptionHandling();
        $this->signIn();
        $response = $this->json('POST', '/api/users/'.auth()->id().'/avatar', [
            'avatar' => 'not_an_image'
        ])->assertStatus(422);
    }

    public function test_user_can_add_avatar_to_his_profile()
    {
        $this->signIn();

        Storage::fake('public');

        $this->json('POST', '/api/users/'.auth()->id().'/avatar', [
            'avatar' => $file = UploadedFile::fake()->image('avatar.jpg')
        ]);

        $this->assertEquals('avatars/'.$file->hashName(), auth()->user()->avatar_path);

        Storage::disk('public')->assertExists('avatars/'. $file->hashName());
    }
}
