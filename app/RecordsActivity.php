<?php
/**
 * Created by PhpStorm.
 * User: henri
 * Date: 03/01/2018
 * Time: 18:05
 */

namespace App;


trait RecordsActivity
{

    protected static function bootRecordsActivity()
    {
        if(auth()->guest()) return;

        foreach (static::getActivitiesToRecord() as $event){
            static::$event(function ($model) use ($event){
                $model->recordActivity($event);
            });
        }
    }

    protected static function getActivitiesToRecord()
    {
        return ['created'];
    }

    protected function recordActivity($event)
    {
        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event),
        ]);
    }

    public function activity()
    {
        return $this->morphMany('App\Activity','subject');
    }

    protected function getActivityType($event)
    {
        $type = strtolower(class_basename($this));
        return "{$event}_{$type}";
    }
}