<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;


class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;



    public function setUp()
    {
        parent::setUp();

        $this->thread= create('App\Thread');
    }

    /** @test */
    function a_user_can_view_all_threads()
    {

        $response = $this->get('/threads')
            -> assertSee($this->thread->title);

    }
    /** @test */
    function  a_user_can_read_a_single_thread()
    {

        $this->get($this->thread->path())
            ->assertSee($this->thread->title);
    }
    /** @test */
    function a_user_can_read_replies_that_are_associated_with_a_thread()
    {

        $reply=create('App\Reply',['thread_id'=>$this->thread->id]);

         $this->get($this->thread->path())->assertSee($reply->body);
    }

    /** @test */
    function a_user_can_read_threads_according_to_a_channel()
    {
        $channel= create("App\Channel");
        $threadInChannel= create("App\Thread",["channel_id"=> $channel->id]);
        $threadNotInChannel= create("App\Thread");

        $this->get('/threads/'. $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);

    }
}