@component('profiles.activities.activity')
    @slot('heading')
        @if($activity->type == 'created_reply')
            {{ $profileUser->name }} replied to
            <a href="{{ $activity->subject->thread->path() }}">{{ $activity->subject->thread->title }}</a>
        @endif
    @endslot
    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent