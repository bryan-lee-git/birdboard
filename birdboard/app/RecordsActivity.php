<?php

namespace App;

trait RecordsActivity
{
    public $oldAttributes = [];

    public static function bootRecordsActivity()
    {
        foreach(self::recordableEvents() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($model->activityDescription($event));
            });
            if ($event === 'updated') {
                static::updating(function ($model) {
                    $model->oldAttributes = $model->getOriginal();
                });
            }
        }
    }

    public function recordActivity($description)
    {
        return $this->activity()->create([
            'description' => $description,
            'changes' => $this->activityChanges($description),
            'userId' => ($this->project ?? $this)->owner->id,
            'projectId' => class_basename($this) === 'Project' ? $this->id : $this->projectId
        ]);
    }

    protected function activityChanges()
    {
        if ($this->wasChanged()) {
            return [
                'before' => array_diff($this->oldAttributes, $this->getAttributes()),
                'after' => $this->getChanges()
            ];
        }
    }

    public function activity()
    {
        return class_basename($this) === 'Project'
            ? $this->hasMany(Activity::class, 'projectId')->latest()
            : $this->morphMany(Activity::class, 'subject')->latest();
    }

    private static function recordableEvents()
    {
        return isset(static::$recordableEvents)
            ? static::$recordableEvents
            : $recordableEvents = ['created', 'updated', 'deleted'];
    }

    protected function activityDescription($description)
    {
        return strtolower(class_basename($this)) . '_' . $description;
    }
}
