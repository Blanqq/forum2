<?php

namespace Tests\Feature;

use App\Mail\PleaseConfirmYourEmail;
use App\User;
use Illuminate\Auth\Events\Registered;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;
    public function test_a_confirmation_email_is_sent_while_registration()
    {
        Mail::fake();

        $this->post(route('register'), [
            'name' => 'JohnJohn',
            'email' => 'johnjohn@example.com',
            'password' => 'john123',
            'password_confirmation' => 'john123'
        ]);

        Mail::assertQueued(PleaseConfirmYourEmail::class);
    }

    public function test_user_can_fully_confirm_their_email_address()
    {
        Mail::fake();
        $this->post(route('register'), [
            'name' => 'JohnJohn',
            'email' => 'johnjohn@example.com',
            'password' => 'john123',
            'password_confirmation' => 'john123'
        ]);
        $user = User::whereName('JohnJohn')->first();

        $this->assertFalse($user->confirmed);
        $this->assertNotNull($user->confirmation_token);

        $response = $this->get(route('register.confirm', ['token' => $user->confirmation_token]));

        $this->assertTrue($user->fresh()->confirmed);

        $response->assertRedirect(route('threads'));

        $this->assertNull($user->fresh()->confirmation_token);
    }

    public function test_confirming_with_invalid_token()
    {
        $this->get(route('register.confirm'), ['token' => 'invalid'])
            ->assertRedirect(route('threads'))
            ->assertSessionHas('flash', 'Invalid email confirming token');
    }

}
