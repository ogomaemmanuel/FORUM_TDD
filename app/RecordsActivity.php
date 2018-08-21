<?php
/**
 * Created by PhpStorm.
 * User: eogoma
 * Date: 8/19/18
 * Time: 10:33 PM
 */

namespace App;


trait RecordsActivity
{

    protected static function bootRecordsActivity()
    {

        if (auth()->guest()) {
            return;
        }

        foreach (static::getActivitiesToRecord() as $event) {

            static::created(function ($model) use ($event) {

                $model->recordActivity($event);
            });

        }

        static::deleting(function ($model) {

            $model->activity()->delete();
        });

    }


    protected static function getActivitiesToRecord()
    {
        return ["created"];
    }

    protected function recordActivity($event)
    {
        $this->activity()->create([
            "type" => $this->getActivityType($event),
            "user_id" => auth()->id(),
        ]);
    }


    protected function activity()
    {
        return $this->morphMany("App\Activity", "subject");
    }

    protected function getActivityType($event)
    {
        $type = strtolower((new \ReflectionClass
        ($this))->getShortName());


        return "{$event}_{$type}";
    }
}