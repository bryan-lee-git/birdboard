<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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

    public function activities()
    {
        return $this->hasMany(Activity::class, 'projectId');
    }

    public function addTask($body)
    {
        return $this->tasks()->create(compact('body'));
    }
}
