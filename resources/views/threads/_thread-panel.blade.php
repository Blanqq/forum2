<div  class="panel panel-default" v-if="editing">

    <div class="panel-heading">
        <div class="level">
            <span class="flex">
                <img src="{{ $thread->author->avatar_path }}" alt="" width="30" height="30">
                Written by <a href="/profiles/{{$thread->author->name}}">{{$thread->author->name}}</a> {{$thread->created_at}}
                <h1>
                    <textarea class="form-control" v-model="title"></textarea>
                </h1>
            </span>
            @can ('update', $thread)
                <form action="{{$thread->path()}}" method="POST">
                    {{csrf_field()}}
                    {{method_field('DELETE')}}
                    <button type="submit" class="btn btn-default">Delete</button>
                </form>
            @endcan

        </div>
    </div>
    <div class="panel-body">
        <textarea class="form-control" v-model="body"></textarea>
        <button class="btn btn-primary" @click.prevent="update">Update</button>
        <button class="btn btn-default" @click="cancel">Cancel</button>
        <hr>
    </div>
    @can('update', $thread)
        <div class="panel-footer" v-show="!editing">
            <button class="btn btn-default btn-xs" @click="editing = true" >Edit Thread</button>
        </div>
    @endcan
</div>

<div  class="panel panel-default" v-else>

    <div class="panel-heading">
        <div class="level">
            <span class="flex">
                <img src="{{ $thread->author->avatar_path }}" alt="" width="30" height="30">
                Written by <a href="/profiles/{{$thread->author->name}}">{{$thread->author->name}}</a> {{$thread->created_at}}
                <h1 v-text="title"></h1>
            </span>
            @can ('update', $thread)
                <form action="{{$thread->path()}}" method="POST">
                    {{csrf_field()}}
                    {{method_field('DELETE')}}
                    <button type="submit" class="btn btn-default">Delete</button>
                </form>
            @endcan

        </div>
    </div>
    <div class="panel-body" v-text="body">
        <hr>
    </div>
    @can('update', $thread)
        <div class="panel-footer">
            <button class="btn btn-xs-default" @click="editing = true">Edit Thread</button>
        </div>
    @endcan
</div>