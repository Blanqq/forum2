<?php

namespace Tests\Unit;


use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Inspections\Spam;

class SpamTest extends TestCase
{
    public function test_it_validates_spam_for_invalid_keywords()
    {
        $spam = new Spam();

        $this->assertFalse($spam->detect('Innocent reply here'));

        $this->expectException('Exception');

        $spam->detect('yahoo customer support');
    }

    public function test_it_validates_spam_for_key_held_down()
    {
        $spam = new Spam();

        $this->expectException('Exception');

        $spam->detect('This is body aaaaaaaaaaaaaaa');
    }
}
