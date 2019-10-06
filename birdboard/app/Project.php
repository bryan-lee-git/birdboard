<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Activity;

class Project extends Model
{
    protected $guarded = [];

    public function path()
    {
        return "/projects/{$this->id}";
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'ownerId');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'projectId');
    }

    public function activity()
    {
        return $this->hasMany(Activity::class, 'projectId')->latest();
    }

    public function addTask($body)
    {
        return $this->tasks()->create(compact('body'));
    }

    public function recordActivity($description)
    {
        return $this->activity()->create(compact('description'));
    }
}
