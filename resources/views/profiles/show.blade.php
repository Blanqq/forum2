@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-header">
            <img src="{{ $profileUser->avatar() }}" alt="" width="100" height="100">
            <h2>{{$profileUser->name}}</h2>
            Account created at {{$profileUser->created_at}}
            @can('update', $profileUser)
                @if(!$profileUser->avatar_path)
                    <form method="POST" action="/api/users/{{ auth()->id() }}/avatar" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <input type="file" name="avatar">
                        <button type="submit" class="btn btn-primary">Add Avatar</button>
                    </form>
                @endif
            @endcan
        </div>
        @forelse($activities as $date => $activity)  {{-- earlier $profileUser->Treads now that way cause separated in ProfileController --}}
            <div class="page-header"><h1>{{$date}}</h1></div>
            @foreach($activity as $record)
                @if(view()->exists("profiles.activities.{$record->type}"))
                    @include("profiles.activities.{$record->type}", ['activity' => $record])
                @endif
            @endforeach
        @empty
            <h1>There is no activity for this user yet.</h1>
        @endforelse
        {{--{{$threads->links()}}  --}}{{-- paginate buttons/links --}}
    </div>


@endsection