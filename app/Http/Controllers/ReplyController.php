<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;

use Illuminate\Http\Request;

class ReplyController extends Controller
{
    public function store($channelId, Thread $thread)
    {
        $this->validate(request(), ['body' => 'required']);


        $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);
        
        return back()->with('flash', 'Your reply has been posted');
    }
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);
        $reply->delete();

        return back();
    }
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);
        $reply->update(['body' => request('body')]);
    }
   
}
