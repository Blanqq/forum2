@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="{{ asset('css/vendor/jquery.atwho.css') }}">
@endsection

@section('content')
<thread-view :data-thread="{{ $thread }}" inline-template>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                @include('threads._thread-panel')

                <replies @added="repliesCount++" @removed="repliesCount--"></replies>


                {{--{{ $replies->links() }}--}}


            </div>


            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h1>{{ $thread->title }}</h1>
                    </div>

                    <div class="panel-body">
                        <p>Thread was created at {{$thread->created_at->diffForHumans()}} by <a href="#">{{$thread->author->name}}</a>
                        and have <span v-text="repliesCount"></span> {{str_plural('reply', $thread->replies_count)}}</p>
                    </div>
                    @if(auth()->check())
                    <div class="panel-footer">
                        <subscribe-button :is-subscribed="{{ json_encode($thread->isSubscribedTo) }}"
                                          v-if="signedIn"></subscribe-button>
                        <button class="btn btn-danger" v-if="authorize('isAdmin') && !locked" @click="lock">Lock Thread</button>
                        <button class="btn btn-primary" v-if="authorize('isAdmin') && locked" @click="unlock">Unlock Thread</button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</thread-view>

@endsection