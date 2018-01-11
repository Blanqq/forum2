<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $guarded = [];
    public function getRouteKeyName(){
        return 'slug';            //laravel takes by default object by id, this changes to take by slug
    }

    public function threads(){
        return $this->hasMany(Thread::class);
    }
}
