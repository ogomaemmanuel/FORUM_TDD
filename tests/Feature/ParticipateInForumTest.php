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
       $this->withExceptionHandling();

       $this->post('/threads/some-channel/1/replies',[])
           ->assertRedirect('/login');

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

   /** @test */
   function a_reply_require_a_body(){
       $this->withExceptionHandling()->signIn();
       $thread=create('App\Thread');
       $reply= make('App\Reply',["body"=> null]);

       $this->post($thread->path().'/replies', $reply->toArray())
           ->assertSessionHasErrors('body');

   }
}
