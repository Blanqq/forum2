<div id="reply-{{ $reply->id }}" class="panel panel-default">
    <div class="panel-heading">

            <div class="row">
                <div class="col-xs-10">

                    Written:
                    {{ $reply->created_at->diffForHumans()}} by
                    <a href="/profiles/{{$reply->owner->name }}">
                        {{$reply->owner->name }}
                    </a>

                </div>

                <div class="col-xs-2">

                    <form class="pull-right" method="POST" action="/replies/{{$reply->id}}/favorites">
                        {{$reply->favorites_count}}
                        {{csrf_field()}}
                        <button type="submit" class="btn btn-default" {{$reply->isFavorited() ? 'disabled': ''}}>Like</button>

                    </form>

                </div>
            </div>


    </div>
    <div class="panel-body">
        {{ $reply->body }}
    </div>

</div>