<?php

use Faker\Generator as Faker;
use App\User;
use App\Channel;
use App\Thread;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'confirmed' => true,
    ];
});

$factory->state(App\User::class, 'unconfirmed', function (){
   return[
       'confirmed' => false
   ];
});

$factory->define(App\Channel::class, function (Faker $faker){
    $name = $faker->word;
    return[
        'name' => $name,
        'slug' => $name
    ];
});

$factory->define(App\Thread::class, function (Faker $faker){
    $title = $faker->sentence;
    return[
        'user_id' => User::all()->count() ? User::all()->random()->id : factory(App\User::class)->create()->id,
        'channel_id' => Channel::all()->count() ? Channel::all()->random()->id : factory(App\Channel::class)->create()->id,
        'replies_count' => 0,
        'visits_count' => 0,
        'title' => $title,
        'body' => $faker->paragraph,
        'slug' => str_slug($title)
    ];
});

$factory->define(App\Reply::class, function (Faker $faker){
    return[
        'user_id' => User::all()->count() ? User::all()->random()->id : factory(App\User::class)->create()->id,
        'thread_id' => $thread_id = Thread::all()->count() ? Thread::all()->random()->id : factory(App\Thread::class)->create()->id,
        'body' => $faker->paragraph
    ];
});

$factory->define(App\Activity::class, function (Faker $faker){  // all fields should be override when fire
    return[
        'user_id' => $faker->randomNumber(),
        'subject_id' => $faker->randomNumber(),
        'subject_type' => $faker->word,
        'type' => $faker->word
    ];
});

$factory->define(\Illuminate\Notifications\DatabaseNotification::class, function(){
    return[
        'id' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
        'type' => 'App\Notifications\ThreadWasUpdated',
        'notifiable_id' => function(){
            return auth()->id() ?: factory('App\User')->create()->id;
        },
        'notifiable_type' => 'App\User',
        'data' => ['foo' => 'bar']
    ];
});



// old
/*$factory->define(App\User::class, function (Faker $faker) {
    static $password;
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});
$factory->define(App\Thread::class, function (Faker $faker){
    return[
        'user_id' => function (){
            return factory(App\User::class)->create()->id;
        },
        'channel_id' => function (){
            return factory(App\Channel::class)->create()->id;
        },
        'replies_count' => 0,
        'title' => $faker->sentence,
        'body' => $faker->paragraph
    ];
});
$factory->define(App\Reply::class, function (Faker $faker){
    return[
        'user_id' =>  function (){
            return factory(App\User::class)->create()->id;
        },
        'thread_id' => function (){
            return factory(App\Thread::class)->create()->id;
        },
        'body' => $faker->paragraph
    ];
});
$factory->define(App\Channel::class, function (Faker $faker){
    $name = $faker->word;
    return[
        'name' => $name,
        'slug' => $name
    ];
});

$factory->define(App\Activity::class, function (Faker $faker){  // all fields should be override when fire
    return[
        'user_id' => $faker->randomNumber(),
        'subject_id' => $faker->randomNumber(),
        'subject_type' => $faker->word,
        'type' => $faker->word
    ];
});*/