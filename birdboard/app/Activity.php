<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guarded = [];

    protected $casts = [
        'changes' => 'array'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'projectId');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function subject()
    {
        return $this->morphTo();
    }
}
