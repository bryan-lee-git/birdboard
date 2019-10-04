@extends('layouts.app')
@section('content')
    <header class="flex items-center mb-3 py-4">
        <div class="flex justify-between w-full items-end text-sm">
            <p class="text-gray-500">
                <a href="/projects">My Projects</a> / {{ $project ->title }}
            </p>
            <a class="button py-2 px-5 rounded text-white shadow" href="/projects/create">Add Project</a>
        </div>
    </header>
    <main>
        <div class="lg:flex -mx-3">
            <div class="lg:w-3/4 px-3 mb-6">
                <div class="mb-8">
                    <h2 class="text-gray-500 mb-3">Tasks</h2>
                    @foreach ($project->tasks as $task)
                        <div class="card mb-3">
                            <form method="POST" action="{{ $task->path() }}">
                                @method("PATCH")
                                @csrf
                                <div class="flex items-center">
                                    <input class="w-full {{ $task->completed ? 'text-gray-400' : ''}}" value="{{ $task->body }}" name="body">
                                    <input {{ $task->completed ? 'checked' : ''}} type="checkbox" name="completed" onChange="this.form.submit()">
                                </div>
                            </form>
                        </div>
                    @endforeach
                    <div class="card mb-3">
                        <form action="{{ $project->path() . '/tasks' }}" method="POST">
                            @csrf
                            <input name="body" class="w-full" type="text" placeholder="Add a new task...">
                        </form>
                    </div>
                </div>
                <div class="mb-8">
                    <h2 class="text-gray-500 mb-3">General Notes</h2>
                    <textarea class="card w-full" style="min-height:200px;">Lorem ipsum.</textarea>
                </div>
            </div>
            <div class="lg:w-1/4 px-3">
                @include('projects.card')
            </div>
        </div>
    </main>
@endsection
