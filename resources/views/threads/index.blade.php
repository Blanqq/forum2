@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            @include('threads._list')
            {{ $threads->appends(request()->input())->links() }}
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Search
                </div>
                <div class="panel-body">
                    <div class="body">
                        <form action="/threads/search" method="GET">
                            <div class="form-group">
                                <input type="text" placeholder="Search..." name="q" class="form-control">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary">Search</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
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