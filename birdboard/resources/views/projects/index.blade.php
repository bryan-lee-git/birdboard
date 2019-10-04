@extends('layouts.app')

@section('content')
    <header class="flex items-center mb-3 py-4">
        <div class="flex justify-between w-full items-center text-sm">
            <h2 class="text-gray-500">My Projects</h2>
            <button class="button py-2 px-5 rounded text-white shadow" href="/projects/create">Add Project</button>
        </div>
    </header>

    <main class="lg:flex lg:flex-wrap -mx-3">
        @forelse ($projects as $project)
            <div class="lg:w-1/3 px-3 pb-6">
                <div class="bg-white rounded-lg shadow p-5" style="height:200px;">
                    <h3 class="font-normal text-xl py-4 -ml-5 border-l-4 border-blue pl-4 mb-3">
                        <a href="{{ $project->path() }}">
                            {{ $project->title }}
                        </a>
                    </h3>
                    <div class="text-gray-500">{{ Str::limit($project->description, 100) }}</div>
                </div>
            </div>
        @empty
            <div>No projects yet!</div>
        @endforelse
    </main>
@endsection
