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
    public function store($channelId, Thread $thread)
    {
        try{
            $this->validateReply();

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
            $this->validateReply();
            $reply->update(['body' => request('body')]);
        }catch (\Exception $e){
            return response('Sorry, your reply could not be saved at this time',422);
        }

    }

    protected function validateReply()
    {
        $this->validate(request(), ['body' => 'required']);
        //dd(Spam::class);
        resolve(Spam::class)->detect(request('body'));
    }
   
}
