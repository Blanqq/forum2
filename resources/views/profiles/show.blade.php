@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-header">
            <h2>{{$profileUser->name}}</h2>
            Account created at {{$profileUser->created_at}}
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