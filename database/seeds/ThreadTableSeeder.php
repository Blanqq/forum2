<?php

use App\Reply;
use App\Thread;
use App\Activity;
use Illuminate\Database\Seeder;

class ThreadTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Thread::class, 20)->create()->each(function( $thread){
            factory(Activity::class)
                ->create(['user_id' => $thread->user_id,
                    'subject_id' => $thread->id,
                    'subject_type' => 'App\Thread',
                    'type' => 'created_thread'
                ]);
            factory(Reply::class, 15)->create(['thread_id' => $thread->id])->each(function ($reply){
                factory(Activity::class)
                    ->create(['user_id' => $reply->user_id,
                        'subject_id' => $reply->id,
                        'subject_type' => 'App\Reply',
                        'type' => 'created_reply'
                    ]);
            });

        });
        factory(Thread::class, 1)->create()->each(function ($thread){
            factory(Activity::class)
                ->create(['user_id' => $thread->user_id,
                    'subject_id' => $thread->id,
                    'subject_type' => 'App\Thread',
                    'type' => 'created_thread'
                ]);
        });
    }
}
