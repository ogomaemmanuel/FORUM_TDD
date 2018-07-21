<?php

namespace Tests\Feature;

use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

   /** @test */

   function unauthenticated_user_may_not_add_replies()
   {
       $this->expectException(AuthenticationException::class);

       $thread=create('App\Thread');

       $reply= create('App\Reply');

       $this->post($thread->path().'/replies',$reply->toArray());

   }
    /** @test */
   function an_authenicated_user_may_participate_in_forum_threads()
   {
       //given a user is authenticated
       $this->signIn();

       //and existing thread
       $thread=create('App\Thread');

       //when a user adds a reply to a thread
       $reply= make('App\Reply');

       $this->post($thread->path().'/replies',$reply->toArray());

       $this->get($thread->path())->assertSee($reply->body);
   }
}
