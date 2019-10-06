<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Project;

class Task extends Model
{
    protected $guarded = [];

    protected $casts = [
        'completed' => 'boolean'
    ];

    protected $touches = ['project'];

    public function path()
    {
        return "/projects/{$this->projectId}/tasks/{$this->id}";
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'projectId');
    }

    public function complete()
    {
        $this->update(['completed' => true]);
        $this->recordActivity('task_completed');
    }

    public function incomplete()
    {
        $this->update(['completed' => false]);
        $this->recordActivity('task_incomplete');
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }

    public function recordActivity($description)
    {
        return $this->activity()->create([
            'description' => $description,
            'projectId' => $this->projectId
        ]);
    }
}
