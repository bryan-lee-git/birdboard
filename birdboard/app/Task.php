<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Project;
use App\RecordsActivity;

class Task extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    protected static $recordableEvents = ['created', 'deleted'];

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
}
