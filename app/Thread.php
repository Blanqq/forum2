<?php

namespace App;

use App\Filters\ThreadFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\ThreadWasUpdated;

class Thread extends Model
{
    use RecordsActivity;

    protected $guarded =[];
    protected $with = ['author', 'channel'];
    protected $append = ['isSubscribedTo'];

    public static function boot()
    {
        parent::boot();
        /*static::addGlobalScope('replyCount', function($builder){
            $builder->withCount('replies');
        });*/
         //if thread was deleted, delete replies to that thread
        static::deleting(function ($thread){
            $thread->replies->each(function ($reply){
                $reply->delete();
            });
        });
//        static::deleting(function ($thread) {
//            $thread->replies->each->delete();
//        });
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
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);
        $this->notifySubscribers($reply);

        //event(new ThreadHasNewReply($this, $reply));
        //$this->subscriptions->where('user_id', '!=', $reply->user_id)->each->notify($reply);

        return $reply;
    }
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }
    public function scopeFilter($query, ThreadFilters $filters)
    {
       return $filters->apply($query);
    }
    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);
        return $this;
    }
    public function unsubscribe($userId = null)
    {
        $this->subscriptions()->where('user_id', $userId ?: auth()->id())->delete();
    }
    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }
    public function getIsSubscribedToAttribute(){
        return $this->subscriptions()->where('user_id', auth()->id())->exists();
    }

    public function notifySubscribers($reply)
    {
        $this->subscriptions->where('user_id', '!=', $reply->user_id)->each->notify($reply);
    }

    public function hasUpdatesFor($user)
    {
        if($user)
        {
            return $this->updated_at >= cache($user->visitedThreadCacheKey($this));
        }
        else
        {
            return false;
        }
    }
}
