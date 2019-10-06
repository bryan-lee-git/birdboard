<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guarded = [];

    public function project()
    {
        return $this->belongsTo(Project::class, 'projectId');
    }

    public function subject()
    {
        return $this->morphTo();
    }
}
