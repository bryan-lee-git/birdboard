@if (count($activity->changes['after']) <= 2)
    {{ ucwords(key($activity->changes['after'])) }} updated by {{ $activity->user->name }}
@else
    Project updated by {{ $activity->user->name }}
@endif
