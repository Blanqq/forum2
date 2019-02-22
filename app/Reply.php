<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reply extends Model
{
    use FavoritingTrait, RecordsActivity;



    protected $guarded =[];
    protected $with = ['owner', 'favorites'];
    protected $appends = ['favoritesCount', 'isFavorited'];  // custom properties who we want to append to this model only getXXXAttribute()

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($thread){
            $thread->favorites->each(function ($favorite){
                $favorite->delete();
                dd('bbb');
            });
        });

        static::deleted(function ($reply){
            $reply->thread->decrement('replies_count');
        });

        static::created(function ($reply){
            $reply->thread->increment('replies_count');
        });
    }

    public function owner() 
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }
    public function path()
    {
        return $this->thread->path()."#reply-{$this->id}";
        //return "/replies/{$this->reply->id}";
    }

    public function wasJustPublished()
    {
        //dd($this->latest()->first()->created_at, Carbon::now()->subMinute());
        return $this->created_at->gt(Carbon::now()->subMinute());
    }

    public function mentionedUsers()
    {
        preg_match_all('/\@(\w+)/', $this->body,$matches);
        return $matches[1];
    }
}
