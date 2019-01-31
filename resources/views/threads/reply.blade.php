<reply :data="{{ $reply }}" inline-template v-cloak>
    {{--with : threated as json--}}
    <div id="reply-{{ $reply->id }}" class="panel panel-default">
        <div class="panel-heading">

            <div class="level">
                <h5 class="flex">

                    Written:
                    {{ $reply->created_at->diffForHumans()}} by
                    <a href="/profiles/{{$reply->owner->name }}">
                        {{$reply->owner->name }}
                    </a>

                </h5>
                @if(Auth::check())
                    <div>
                        <favorite :reply="{{ $reply }}"></favorite>


                        {{--<form class="pull-right" method="POST" action="/replies/{{$reply->id}}/favorites">
                            {{$reply->favorites_count}}
                            {{csrf_field()}}
                            <button type="submit" class="btn btn-default" {{$reply->isFavorited() ? 'disabled': ''}}>Like</button>

                        </form>--}}

                    </div>
                @endif
            </div>


        </div>
        <div class="panel-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>
                <button class="btn btn-xs btn-primary" @click="update">Update</button>
                <button class="btn btn-xs btn-link" @click="editing = false">Cancel</button>
            </div>
            <div v-else v-text="body">

            </div>
        </div>
        @can('update', $reply)
            <div class="panel-footer level">
                <button class="btn btn-xs mr-1" @click="editing = true">Edit</button>
                <button class="btn btn-danger btn-xs" @click="destroy">Delete Reply</button>
                {{--<form method="POST" action="/replies/{{ $reply->id }}" style="display:inline-block;">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button type="submit" class="btn btn-danger btn-xs" >Delete Reply</button>
                </form>--}}
            </div>
        @endcan


    </div>
</reply>