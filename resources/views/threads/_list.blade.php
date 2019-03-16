@forelse ($threads as $thread)
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="level">
                <div class="flex">
                    <h3>
                        <a href="{{$thread->path()}}">
                            @if($thread->hasUpdatesFor(auth()->user()))
                                <strong>{{ $thread->title }}</strong>
                            @else
                                {{ $thread->title }}
                            @endif
                        </a>
                    </h3>
                    Created by
                    <a href="/profiles/{{$thread->author->name}}">{{$thread->author->name}}
                    </a> {{$thread->created_at}}
                </div>

                <p>Have <strong>{{$thread->replies_count}} {{str_plural('reply', $thread->replies_count)}}</strong>
                </p>
            </div>
        </div>

        <div class="panel-body">
            <div class="body">
                {!!$thread->body !!}
            </div>
            <hr>
        </div>
        <div class="panel-footer">
            {{ $thread->visits_count }} Visits
        </div>
    </div>

@empty{{-- jeżeli do danego kanału nie ma przypisanych wątków wyświetl <p>...</p>--}}
<p>There are no threads</p>
@endforelse

