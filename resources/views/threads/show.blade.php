@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div  class="panel panel-default">

                <div class="panel-heading">
                    <div class="level">
                        <span class="flex">
                            Written by {{$thread->author->name}} {{$thread->created_at}}
                            <h1>{{ $thread->title }}</h1>
                        </span>
                        @can ('update', $thread)
                            <form action="{{$thread->path()}}" method="POST">
                                {{csrf_field()}}
                                {{method_field('DELETE')}}
                                <button type="submit" class="btn btn-default">Delete</button>
                            </form>
                        @endcan

                    </div>
                </div>

                <div class="panel-body">
                    {{ $thread->body }}
                    <hr>
                </div>
            </div>
            @foreach($replies as $reply)
                @include('threads.reply')
            @endforeach

            {{ $replies->links() }}

            @if(auth()->check())

                <form method="POST" action="/threads/{{$thread->channel_id}}/{{$thread->id}}/replies">
                    {{csrf_field()}}
                    <div class="form-group">
                        <textarea name="body" id="body" class="form-control" placeholder="Type your answer here" rows="5"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-default pull-right">Post</button>
                    </div>
                </form>
            @else
                <p><a href="{{route('login')}}">Plesase Sign In</a></p>
            @endif
        </div>


        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1>{{ $thread->title }}</h1>
                </div>

                <div class="panel-body">
                    <p>Thread was created at {{$thread->created_at->diffForHumans()}} by <a href="#">{{$thread->author->name}}</a>
                    and have {{$thread->replies_count}} {{str_plural('reply', $thread->replies_count)}}</p>

                </div>
            </div>
        </div>
    </div>
</div>


@endsection