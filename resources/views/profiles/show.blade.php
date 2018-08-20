@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-header">
            <h2>{{$profileUser->name}}</h2>
            Account created at {{$profileUser->created_at}}
        </div>
        @foreach($activities as $date => $activity)  {{-- earlier $profileUser->Treads now that way cause separated in ProfileController --}}
            <div class="page-header"><h1>{{$date}}</h1></div>
            @foreach($activity as $record)
                @include("profiles.activities.{$record->type}", ['activity' => $record])
            @endforeach
        @endforeach
        {{--{{$threads->links()}}  --}}{{-- paginate buttons/links --}}
    </div>


@endsection