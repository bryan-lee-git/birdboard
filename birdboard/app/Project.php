<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Activity;
use App\RecordsActivity;

class Project extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    protected static $recordableEvents = ['updated', 'created'];

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

    public function addTask($body)
    {
        return $this->tasks()->create(compact('body'));
    }
}
