<div class="card" style="height:200px;">
    <h3 class="font-normal text-xl py-4 -ml-5 border-l-4 border-blue pl-4 mb-3">
        <a href="{{ $project->path() }}">
            {{ $project->title }}
        </a>
    </h3>
    <div class="text-gray-500 mb-4">{{ Str::limit($project->description, 90) }}</div>
    <footer class="mb-2">
        <form class="text-right" method="POST" action="{{ $project->path() }}">
            @method("DELETE")
            @csrf
            <button type="submit" class="text-xs">Delete</button>
        </form>
    </footer>
</div>
