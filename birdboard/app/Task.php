<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Project;

class Task extends Model
{
    protected $guarded = [];

    public function path()
    {
        return "/projects/{$this->projectId}/tasks/{$this->id}";
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'projectId');
    }
}
