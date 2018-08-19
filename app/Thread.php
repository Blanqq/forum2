<?php

namespace App;

use App\Filters\ThreadFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;

    protected $guarded =[];
    protected $with = ['author', 'channel'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('replyCount', function($builder){
            $builder->withCount('replies');
        });

        static::deleting(function ($thread){   // if thread was deleted, delete replies to that thread
            $thread->replies()->delete();
        });
    }


    public function path()
    {
        //return '/threads/' . $this->channel->slug. '/' . $this->id;
        return "/threads/{$this->channel->slug}/{$this->id}";
    }
    
    public function replies()
    {
        return $this->hasMany(Reply::class);
            //->withCount('favorites')
            //->with('owner');
    }
    public function getReplyCount(){
        return $this->replies()->count();
    }
    public function author() {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function addReply($reply) {
        $this->replies()->create($reply);
    }
    public function channel(){
        return $this->belongsTo(Channel::class);
    }
    public function scopeFilter($query, ThreadFilters $filters)
    {
       return $filters->apply($query);
    }
}
