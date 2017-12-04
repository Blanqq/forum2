@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Written by {{$thread->author->name}} {{$thread->created_at}}
                    <h1>{{ $thread->title }}</h1>
                </div>

                <div class="panel-body">
                    
                    <div class="body">
                        {{ $thread->body }}
                    </div>
                    
                    <hr>
                    

               
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            
                @foreach($thread->replies as $reply)
                    @include('threads.reply')
                @endforeach
           
        </div>
    </div>
    @if(auth()->check())
        <div class="row">
            <div class="col-md-8 col-md-offset-2">

                    Hello World
                    
                    <form method="POST" action="/threads/{{$thread->id}}/replies">
                        {{csrf_field()}}
                        <div class="form-group">
                            <textarea name="body" id="body" class="form-control" placeholder="Type your answer here" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-default pull-right">Post</button>
                        </div>
                    </form>

            </div>
        </div>
    @else
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <p><a href="{{route('login')}}">Plesase Sign In</a></p>
        </div>
    </div>
    
    @endif
</div>
@endsection