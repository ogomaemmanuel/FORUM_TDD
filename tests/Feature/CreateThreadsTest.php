<?php

namespace Tests\Feature;

use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;


    /** @test */

    function guest_may_not_create_threads()
    {
        $this->withExceptionHandling();

        $this->get('/threads/create')
            ->assertRedirect('/login');

        $this->post('/threads')
            ->assertRedirect('/login');
    }


    /** @test */

    function an_authenticated_user_can_create_new_threads()
    {
        $this->signIn();
        $thread = make('App\Thread');
        $response = $this->post('/threads', $thread->toArray());
        $this->get($response->headers->get('Location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /** @test */
    function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');

    }

    /** @test */
    function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');

    }

    /** @test */
    function a_thread_requires_a_valid_channel()
    {
        factory("App\Channel", 2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');

    }
    /** @test */
    function unathourized_users_may_not_delete_threads()
    {
        $this->withExceptionHandling();

        $thread = create('App\Thread');

        $this->delete($thread->path())
            ->assertRedirect('/login');



        $this->signIn();

        $this->delete($thread->path())
            ->assertRedirect('/login');




    }


   /** @test */
    function authorised_user_can_delete_threads()
    {
        $this->signIn();

        $thread = create('App\Thread',["user_id"=>auth()->id()]);

        $reply =create("App\Reply",['thread_id'=>$thread->id]);

        $this->json("DELETE", $thread->path())
            ->assertStatus(204);


        $this->assertDatabaseMissing('threads', ['id'=>$thread->id]);
        $this->assertDatabaseMissing('replies', ['id'=>$reply->id]);

    }


    function publishThread($overrides)
    {
        $this->withExceptionHandling()->signIn();
        $thread = make("App\Thread", $overrides);
        return $this->post('threads', $thread->toArray());
    }

}
