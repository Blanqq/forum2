<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar_path', 'confirmed'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','email'
    ];

    protected $casts = [
        'confirmed' => 'boolean'
    ];

    public function getRouteKeyName()
    {
        return 'name';
    }
    public function threads()
    {
        return $this->hasMany(Thread::class)->latest();
    }
    public function activity()
    {
        return $this->hasMany(Activity::class);
    }

    public function visitedThreadCacheKey($thread)
    {
        return sprintf("users.%s.visits.%s", $this->id, $thread->id);
    }

    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }

    public function getAvatarPathAttribute($avatar)  //$user->avatar_path
    {
        if(!$avatar)
        {
            return asset('avatars/default.jpg');
        }
        return asset($avatar);
    }
}
