<?php

namespace App;

use App\Filters\ThreadFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\ThreadWasUpdated;
use App\Events\ThreadHasNewReply;

class Thread extends Model
{
    use RecordsActivity;

    protected $guarded =[];
    protected $with = ['author', 'channel'];
    protected $append = ['isSubscribedTo'];

    public function getRouteKeyName()
    {
        return 'slug';
    }



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
        return "/threads/{$this->channel->slug}/{$this->slug}";
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

        event(new ThreadHasNewReply($reply));

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
    public function setSlugAttribute($value)
    {
        if(static::whereSlug($slug = str_slug($value))->exists())
        {
            $slug = $this->incrementSlug($slug);
        }
        $this->attributes['slug'] = $slug;
    }

    public function incrementSlug($slug)
    {
        $maxSlug = static::whereTitle($this->title)->latest('id')->value('slug');
        if(is_numeric($maxSlug[-1]))
        {
            return preg_replace_callback('/\d+$/', function ($matches){return $matches[0]+1;}, $maxSlug);
            //return $newSlug;
        }
        return "{$slug}-2";
    }

}
