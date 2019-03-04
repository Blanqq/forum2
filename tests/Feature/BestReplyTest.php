<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BestReplyTest extends TestCase
{
    use DatabaseMigrations;
    public function test_thread_creator_can_mark_any_reply_as_thr_best()
    {
        $this->signIn();
        $thread = create('App\Thread', ['user_id' => auth()->id() ]);
        $replies = create('App\Reply', ['thread_id' => $thread->id], 2);
        $this->postJson(route('best-replies.store', [$replies[1]->id]));
        $this->assertTrue($replies[1]->fresh()->isBest());
    }

    public function test_only_thread_creator_can_mark_reply_as_best()
    {
        $this->withExceptionHandling();
        $this->signIn();
        $thread = create('App\Thread',  ['user_id' => auth()->id() ]);
        $replies = create('App\Reply', ['thread_id' => $thread->id], 2);

        $this->signIn(create('App\User'));
        $this->postJson(route('best-replies.store', [$replies[1]->id]))->assertStatus(403);
        $this->assertFalse($replies[1]->fresh()->isBest());

    }

    public function test_if_best_reply_is_deleted_thread_best_reply_id_should_be_updated()
    {

        DB::statement('PRAGMA foreign_keys=on;');
        $this->signIn();
        //$thread = create('App\Thread',  ['user_id' => auth()->id() ]);
        $reply = create('App\Reply', ['user_id' => auth()->id()]);
        $reply->thread->setBestReply($reply);

        $this->delete(route('replies.destroy', $reply->id));
        $this->assertNull($reply->thread->fresh()->best_reply_id);
    }
}
