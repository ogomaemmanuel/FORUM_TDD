<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use Illuminate\Http\Request;

class RepliesController extends Controller
{


    /**
     * RepliesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth',['except'=>'index']);
    }

    public function index($channelId,Thread $thread){

      return $thread->replies()->paginate(20);
    }

    public function store($channelid, Thread $thread)
    {

        $this->validate(request(), [
            'body' => 'required'
        ]);

       if( stripos(request("body"),'yahoo customer support')!==false){
           throw new \Exception("Your Reply Contains Spam.");
       };

       $reply= $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id(),
        ]);

        if(request()->expectsJson()){
            return $reply->load('owner');
        }

        return back()->with('flash', 'Your reply has been left.');
    }


    public function destroy(Reply $reply)
    {

        $this->authorize("update", $reply);

        $reply->delete();

        if(request()->expectsJson()){

            return  response(["status"=>'Reply deleted']);
        }

        return back();
    }

    public function update(Reply $reply)
    {
       $this->authorize("update",$reply);
        $reply->update(request(["body"]));
    }
}
