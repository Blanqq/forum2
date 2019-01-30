<?php

namespace App;

trait FavoritingTrait{

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function favorite()
    {
        $attributes = ['user_id' => auth()->id()];
        if (!$this->favorites()->where($attributes)->exists()) {
            $this->favorites()->create($attributes);
        }

    }

    public function unfavorite()
    {
        $attributes = ['user_id' => auth()->id()];

        // each to ensure thad model event will fire, to fire event model need to delete model, not like previously make sql query
        /*$this->favorites()->where($attributes)->get()->each(function($favorite){
            $favorite->delete();
        });*/
        $this->favorites()->where($attributes)->get()->each->delete();
    }

    public function isFavorited()
    {
        return !!$this->favorites->where('user_id', auth()->id())->count();
    }

    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    public function getFavoritesCountAttribute()
    {   ///Attribute is a key word must be in name of function
        return $this->favorites->count();
    }
}