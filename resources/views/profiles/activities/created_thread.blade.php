@component('profiles.activities.activity')
    @slot('heading')
        @if($activity->type == 'created_thread')
            {{ $profileUser->name }} published
            <a href="{{ $activity->subject->path() }}">{{ $activity->subject->title }}</a>
        @endif
    @endslot
    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent