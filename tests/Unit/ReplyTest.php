<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReplyTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseMigrations;
    public function test_it_has_an_owner() 
    {
        $reply = factory('App\Reply')->create();
        
        $this->assertInstanceOf('App\User', $reply->owner);
    }

    public function test_it_knows_it_was_just_published()
    {
        $reply = factory('App\Reply')->create();
        $this->assertTrue($reply->wasJustPublished());

    }
}
