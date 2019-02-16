<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilters;
use App\Thread;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Rules\SpamFree;

class ThreadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware('auth')->except(['index','show']);
    }
    public function index(Channel $channel, ThreadFilters $filters)
    {
        /*if($channel->exists){
            //$channelId = Channel::where('slug', $channelSlug)->first()->id;
            //$threads = Thread::where('channel_id', $channelId)->latest()->get();
            $threads = $channel->threads()->latest();
        }else{
            $threads = Thread::latest();
        }if($username = request('by')){
            $user = \App\User::where('name', $username)->firstOrFail();
            $threads->where('user_id', $user->id);
        }
        $threads = $threads->get();*/

        $threads = $this->getThreads($channel, $filters);

        if (request()->wantsJson()){
            return $threads;
        }


        return view('threads.index', compact('threads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'title' => ['required', new SpamFree],
            'body' => ['required', new SpamFree],
            'channel_id' => 'required|exists:channels,id'
        ]);

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => request('channel_id'),
            'title' => request('title'),
            'body' => request('body')
        ]);
        return redirect($thread->path())->with('flash', 'Your thread has been created');
        //return view('threads.show');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show(Channel $channel, Thread $thread)
    {

        if(auth()->check())
        {
            cache()->forever(auth()->user()->visitedThreadCacheKey($thread), Carbon::now());
        }


        //return $thread->getReplyCount();
        //return $thread->replies;
        return view('threads.show', compact('thread') );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        //
    }


    public function destroy($channel, Thread $thread)
    {
    /*    if($thread->user_id != auth()->id()) {
            abort(403, 'You do not have rights to delete this thread');
            //if(\request()->wantsJson()){
                //return response(['status' => 'Access Forbidden. Permission Denied'], 403);
            //}
            return redirect('/login');
        }
    */
        $this->authorize('update', $thread);   // authorize update request on thread if not authorized laravel automaticaly throws 403

        //$thread->replies()->delete(); // dont delete replies if using model events
        $thread->delete();

        if(\request()->wantsJson()){
            return response([], 204);   // 204 - No Content
        }

        return redirect('/threads');
    }

    protected function getThreads(Channel $channel, ThreadFilters $filters)
    {
        //$threads = Thread::with('channel')->latest()->filter($filters);  // with loads relations not needed if relarion in $with field in model (then automatic)
        $threads = Thread::latest()->filter($filters);
        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }
        return $threads->get();
    }
}
