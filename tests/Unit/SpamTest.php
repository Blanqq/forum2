<?php

namespace Tests\Unit;


use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SpamTest extends TestCase
{
    public function test_it_validates_spam()
    {
        $spam = new \App\Spam();

        $this->assertFalse($spam->detect('Innocent reply here'));
    }
}
