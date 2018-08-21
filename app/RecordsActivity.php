<?php

namespace App;

trait RecordsActivity
{
    public static function bootRecordsActivity()
    {
        if (auth()->guest())
            return;

        foreach (static::getActivitiesToRecord() as $event)
        {
            static::$event(function ($model) use ($event) {
               $model->recordActivity($event);
            });
        }
        static::deleting(function ($model){
            $model->activity()->delete();
        });
        /*static::created(function ($thread){   // every time a thread is created this function runs
            $thread->recordActivity('created');
        });*/
    }

    public static function getActivitiesToRecord()
    {
        return ['created'];  // type of events that should be get
    }

    public function recordActivity($event)
    {
        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event),
        ]);
/*        Activity::create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event),
            'subject_id' => $this->id,
            'subject_type' => get_class($this),
        ]);*/
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    public function getActivityType($event)
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());
        return "{$event}_{$type}";  // inline return $event.'_'.$type;
    }
}