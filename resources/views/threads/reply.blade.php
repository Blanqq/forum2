<div class="panel panel-default">
    <div class="panel-heading">
        Written: 
        {{ $reply->created_at->diffForHumans()}} by 
        <a href="#"> 
            {{$reply->owner->name }}
        </a>
    </div>            
    <div class="panel-body">

        <div class="body">
            {{ $reply->body }}
        </div>
        <hr>
        
    </div>
</div>