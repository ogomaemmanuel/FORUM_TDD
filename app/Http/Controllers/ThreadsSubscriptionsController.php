<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;

class ThreadsSubscriptionsController extends Controller
{
    //

    public function store ($channelId, Thread $thread){
        $thread->subscribe();
}
}
