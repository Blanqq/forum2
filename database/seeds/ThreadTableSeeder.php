<?php

use App\Reply;
use App\Thread;
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
        factory(Thread::class, 50)->create()->each(function( $thread){
            factory(Reply::class, 15)->create(['thread_id' => $thread->id]);
        });
    }
}
