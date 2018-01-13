@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Forum Threads</div>

                <div class="panel-body">
                    @foreach ($threads as $thread)
                    <div class="level">
                        <h3>
                            <a href="{{$thread->path()}}">
                                {{ $thread->title }}
                            </a>
                        </h3>
                        <strong>{{$thread->replies_count}} {{str_plural('comment', $thread->replies_count)}}</strong>

                    </div>
                    <div class="body">
                        {{ $thread->body }}
                    </div>
                    
                    <hr>
                    @endforeach


                </div>
            </div>
        </div>
    </div>
</div>
@endsection