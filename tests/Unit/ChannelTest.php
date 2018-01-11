<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChannelTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    use \Illuminate\Foundation\Testing\DatabaseMigrations;

    public function test_channel_have_threads()
    {
        $channel = create('App\Channel'); //create channel
        $thread = create('App\Thread', ['channel_id' => $channel->id]); //asign thread to a channel
        $this->assertTrue($channel->threads->contains($thread));  //wykonaj relacje, sprawdz czy zawiera utworzony watek
    }
}
