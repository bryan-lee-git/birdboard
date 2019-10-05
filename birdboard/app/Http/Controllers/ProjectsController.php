<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;

class ProjectsController extends Controller
{
    public function index()
    {
        $projects = auth()->user()->projects;
        return view('projects.index', compact('projects'));
    }

    public function store()
    {
        $project = auth()->user()->projects()->create(request()->validate([
            'title' => 'required',
            'description' => 'required',
            'notes' => ''
        ]));

        return redirect($project->path());
    }

    public function show(Project $project)
    {
        $this->authorize('update', $project);
        return view('projects.show', compact('project'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function update(Project $project)
    {
        $this->authorize('update', $project);
        request()->validate(['notes' => 'required']);
        $project->update(request(['notes']));
        return redirect($project->path());
    }
}
