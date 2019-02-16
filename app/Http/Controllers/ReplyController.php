<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Rules\SpamFree;
use App\Thread;
use Illuminate\Http\Request;

class ReplyController extends Controller
{

    public function index($chanelSlug, Thread $thread)
    {
        return $thread->replies()->paginate(5);
    }
    public function store($channelId, Thread $thread)
    {
        try{
            //$this->validate(request(), ['body' => 'required|spamfree']);
            $this->authorize('create', new Reply);
            request()->validate([
                'body' => ['required', new SpamFree]
            ]);
            $reply = $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id()
            ]);
        }catch (\Exception $e){
            return response('Sorry, your reply could not be saved at this time',422);
        }

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
        try{
            request()->validate([
                'body' => ['required', new SpamFree]
            ]);
            $reply->update(['body' => request('body')]);
        }catch (\Exception $e){
            return response('Sorry, your reply could not be saved at this time',422);
        }

    }
   
}
