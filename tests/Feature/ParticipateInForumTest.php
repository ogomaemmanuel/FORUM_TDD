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

       $this->assertDatabaseHas('replies',['body'=>$reply->body]);
       $this->assertEquals(1,$thread->fresh()->replies_count);
   }

   /** @test */
   function a_reply_require_a_body(){
       $this->withExceptionHandling()->signIn();
       $thread=create('App\Thread');
       $reply= make('App\Reply',["body"=> null]);

       $this->post($thread->path().'/replies', $reply->toArray())
           ->assertSessionHasErrors('body');

   }
    /** @test */
   function unauthorised_users_cannot_delete_replies(){

       $this->withExceptionHandling();

       $reply =create(\App\Reply::class);

       $this->delete("/replies/{$reply->id}")

           ->assertRedirect("login");

       $this->signIn()->delete("/replies/{$reply->id}")

           ->assertStatus(403);


   }

   /** @test */
   function authorised_users_cannot_delete_replies(){

       $this->withExceptionHandling();

       $this ->signIn();

       $reply =create(\App\Reply::class,["user_id"=> auth()->id()]);

       $this->delete("/replies/{$reply->id}")->assertStatus(302);

       $this->assertDatabaseMissing("replies",["id"=>$reply->id]);
       $this->assertEquals(0,$reply->thread->fresh()->replies_count);
   }

   /** @test */
   function authorised_users_cannot_update_replies(){

//       $this->withExceptionHandling();

       $this ->signIn();

        $updatedReply="You have been changed fool.";

       $reply =create(\App\Reply::class,["user_id"=> auth()->id()]);

       $this->patch("/replies/{$reply->id}",["body"=>$updatedReply]);


       $this->assertDatabaseHas("replies",[
           "id"=>$reply->id,
           "body"=>$updatedReply
       ]);
   }

   /** @test */
   function un_authorised_users_cannot_update_replies(){

       $this->withExceptionHandling();

        $updatedReply="You have been changed fool.";

       $reply =create(\App\Reply::class);

       $this->patch("/replies/{$reply->id}")
           ->assertRedirect("login");

       $this->signIn()
       ->patch("/replies/{$reply->id}")
           ->assertStatus(403);



   }


}
