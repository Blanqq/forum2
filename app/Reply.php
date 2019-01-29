<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use FavoritingTrait, RecordsActivity;

    protected $guarded =[];
    protected $with = ['owner', 'favorites'];
    protected $appends = ['favoritesCount', 'isFavorited'];  // custom properties who we want to append to this model only getXXXAttribute()

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
}
