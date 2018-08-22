<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UsersNotificationsController extends Controller
{
    //


    function __construct()
    {
        $this->middleware("auth");
    }

    /**
     * @param User $user
     * @param      $notificationId
     */
    public function destroy(User $user, $notificationId)
    {
        auth()->$user()->notifications()->findOrFail($notificationId)
            ->markAsRead();
    }

    function index(){
        return auth()->user()->unreadNotifications;
    }
}
