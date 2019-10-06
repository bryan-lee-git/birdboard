<div class="card mt-3">
    <ul class="text-xs">
        @foreach ($project->activity as $activity)
            <li class="{{ !$loop->last ? 'mb-2' : ''}}">
                @include("projects.activity.$activity->description")
                <p class="text-gray-500">
                    {{ $activity->created_at->diffForHumans() }}
                </p>
            </li>
        @endforeach
    </ul>
</div>
