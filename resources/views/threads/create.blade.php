@extends('layouts.app')

@section('header')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Create a New Thread</div>

                <div class="panel-body">
                    
                    <form method="POST" action="/threads" >
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="title" value="{{old('title')}}" required>
                        </div>
                        <div class="form-group">
                            <label for="body">Body:</label>
                            <textarea name="body" class="form-control" id="body" value="{{old('body')}}" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="body">Channel:</label>
                            <select name="channel_id" class="form-control" required>
                                <option value="">Select Channel</option>
                                @foreach ($channels as $channel)  //channels send through appserviceprovider
                                    <option value="{{$channel->id}}" {{old('channel_id') == $channel->id ? 'selected' : ''}}>{{$channel->slug}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="g-recaptcha" data-sitekey="6LdYQJYUAAAAAP5QWIhYV_a5LoaUPmRBFdNble92"></div>


                    @if (count($errors))
                            <ul class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        @endif
                        <button type="submit" class="btn btn-default">Create Thread</button>
                    </form>
               
                </div>
            </div>
        </div>
    </div>
</div>
@endsection