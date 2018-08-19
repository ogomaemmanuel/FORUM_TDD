<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilters;
use App\Thread;
use Illuminate\Http\Request;

class ThreadsController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * @param Channel $channel
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Channel $channel, ThreadFilters $filters)
    {


        $threads = $this->getThreads($channel, $filters);
        if (request()->wantsJson()) {
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
        return view("threads.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'channel_id' => 'required|exists:channels,id'
        ]);
        $thread = Thread::create(
            [
                'user_id' => auth()->id(),
                'title' => request('title'),
                'channel_id' => request('channel_id'),
                'body' => request('body'),
            ]
        );
        return redirect($thread->path());
    }

    /**
     * Display the specified resource.
     *
     * @param              $channelId
     * @param  \App\Thread $thread
     *
     * @return \Illuminate\Http\Response
     */
    public function show($channel, Thread $thread)
    {
        return view('threads.show',
            [
                "thread" => $thread,
                "replies" => $thread->replies()->paginate(25)
            ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread $thread
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Thread              $thread
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread $thread
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($channel, Thread $thread)
    {
        //
        $thread->delete();


        if (request()->wantsJson()) {
            return response([], 204);
        }

        return redirect("/threads");
    }

    /**
     * @param Channel       $channel
     * @param ThreadFilters $filters
     *
     * @return mixed
     */
    protected function getThreads(Channel $channel, ThreadFilters $filters)
    {
        $threads = Thread::latest()->filter($filters);

        if ($channel->exists) {
            $threads->where("channel_id", $channel->id);
        }
//
//        dd($threads->toSql());
        $threads = $threads->get();


        return $threads;
    }
//
//    /**
//     * @param Channel $channel
//     *
//     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection|static|static[]
//     */
//    protected function getThreads(Channel $channel)
//    {
//        if ($channel->exists) {
//            $threads = $channel->threads()->latest();
//        } else {
//            $threads = Thread::latest();
//        }
//        if ($username = request('by')) {
//            $user = \App\User::where('name', $username)->firstOrFail();
//
//            $threads->where('user_id', $user->id);
//        }
//
//        $threads = $threads->get();
//        return $threads;
//    }
}
