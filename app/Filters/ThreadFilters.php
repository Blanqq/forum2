<?php
namespace App\Filters;
use App\User;
class ThreadFilters extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['by', 'popularity', 'unanswered'];
    /**
     * Filter the query by a given username.
     *
     * @param  string $username
     * @return Builder
     */
    protected function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();
        return $this->builder->where('user_id', $user->id);
    }
    protected function popularity(){
        $this->builder->getQuery()->orders = []; //clear all filters
        return $this->builder->orderBy('replies_count', 'desc');
    }
    protected function unanswered()
    {
        $this->builder->getQuery()->orders = []; //clear all filters
        return $this->builder->where('replies_count', 0);
    }
}