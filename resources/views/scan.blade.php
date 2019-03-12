@extends('layouts.app')

@section('content')
    <div class="container">
        <search :data-app-id="'{{ config('scout.algolia.id') }}'"
                :data-algolia-key="'{{ config('scout.algolia.key') }}'">
        </search>
    </div>

@endsection