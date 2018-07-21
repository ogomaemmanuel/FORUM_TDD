<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

   /** @test */
   function an_authenicated_user_may_participate_in_forum_threads()
   {
       //given a user is authenticated
       $this->be(factory('App\User')->create());

       //and existing thread
       $thread=factory('App\Thread')->create();

       //when a user adds a reply to a thread
       $reply= factory('App\Reply')->create();

       $this->post('/threads/'.$thread->id.'/replies',$reply->toArray());

       $this->get($this->thread->path())->assertSee($reply->body);
   }
}
