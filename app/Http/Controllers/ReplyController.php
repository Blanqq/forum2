<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use App\Inspections\Spam;

use Illuminate\Http\Request;

class ReplyController extends Controller
{

    public function index($chanelSlug, Thread $thread)
    {
        return $thread->replies()->paginate(5);
    }
    public function store($channelId, Thread $thread, Spam $spam)
    {
        $this->validate(request(), ['body' => 'required']);

        $spam->detect(request('body'));

        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);
        if(request()->expectsJson()){
            return $reply->load('owner');
        }
        return back()->with('flash', 'Your reply has been posted');
    }
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);
        $reply->delete();

        if(request()->expectsJson())
        {
            return response(['status' => 'Replydeleted']);
        }
        return back();
    }
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);
        $reply->update(['body' => request('body')]);
    }
   
}
