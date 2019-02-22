<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateReplyRequest;
use App\Reply;
use App\Rules\SpamFree;
use App\Thread;
use App\User;
use App\Notifications\UserWasMentioned;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    public function __construct() {
        $this->middleware('auth')->except(['index', 'show']);
    }
    public function index($chanelSlug, Thread $thread)
    {
        return $thread->replies()->paginate(5);
    }
    public function store($channelId, Thread $thread, CreateReplyRequest $createReplyRequest)
    {
            $reply = $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id()
            ]);

            return $reply->load('owner');

        //return back()->with('flash', 'Your reply has been posted');
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
        request()->validate([
            'body' => ['required', new SpamFree]
        ]);
        $reply->update(['body' => request('body')]);
    }
   
}
