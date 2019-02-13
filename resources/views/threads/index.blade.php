@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @forelse ($threads as $thread)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="level">
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
                            </a> {{$thread->created_at}} and have
                            <strong>{{$thread->replies_count}} {{str_plural('comment', $thread->replies_count)}}</strong>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="body">
                            {{ $thread->body }}
                        </div>
                        <hr>
                    </div>
                </div>
            @empty{{-- jeżeli do danego kanału nie ma przypisanych wątków wyświetl <p>...</p>--}}
                <p>There are no threads</p>
            @endforelse
        </div>
    </div>
</div>
@endsection