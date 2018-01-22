@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-header">
            <h2>{{$profileUser->name}}</h2>
            Account created at {{$profileUser->created_at}}
        </div>
        @foreach($threads as $thread)  {{-- earlier $profileUser->Treads now that way cause separated in ProfileController --}}
            <div class="panel panel-default">
                <div class="panel-heading">
                    Written by <a href="/profiles/{{$thread->author->name}}">
                        {{$thread->author->name}}
                    </a> {{$thread->created_at->diffForHumans()}}
                    <h1><a href="{{$thread->path()}}">{{ $thread->title }}</a></h1>
                </div>

                <div class="panel-body">
                    {{ $thread->body }}
                    <hr>
                </div>
            </div>
        @endforeach
        {{$threads->links()}}  {{-- paginate buttons/links --}}
    </div>


@endsection