@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            @include('threads._list')
            {{ $threads->appends(request()->input())->links() }}
        </div>
        <div class="col-md-4">
            @if(count($trending))
            <div class="panel panel-default">
                <div class="panel-heading">
                    Trending threads
                </div>
                <div class="panel-body">
                    <div class="body">
                        @forelse($trending as $thread)
                            <p><a href="{{$thread->path}}">{{ $thread->title }}</a></p>
                        @empty
                            <p>No trending threads.</p>
                        @endforelse
                    </div>

                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection