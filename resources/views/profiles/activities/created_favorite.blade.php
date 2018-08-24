@component('profiles.activities.activity')
    @slot('heading')
        @if($activity->type == 'created_favorite')
            <a href="{{ $activity->subject->favorited->path() }}">
                {{ $profileUser->name }} favorited a reply
            </a>

            {{--<a href="{{ $activity->subject->path() }}">{{ $activity->subject->title }}</a>--}}
        @endif
    @endslot
    @slot('body')
        {{ $activity->subject->favorited->body }}
    @endslot
@endcomponent