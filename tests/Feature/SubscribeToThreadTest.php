<?php

use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseMigrations;


class SubscribeToThreadTest extends TestCase
{
    Use DatabaseMigrations;
    /** @test */
    public function a_user_can_subscribe_to_threads()
    {
        $this->signIn();

        $thread= create("App\Thread");

        $this->post($thread->path()."/subscriptions");

        $thread->addReply([
            "user_id"=>auth()->id(),
            "body"=>"some reply here",
        ]);
    }
    /** @test */
    public function a_user_can_unsubscribe_from_threads()
    {
        $this->signIn();
        $thread= create("App\Thread");

        $thread->subscribe();

        $this->delete($thread->path()."/subscriptions");

        $this->assertCount(0,$thread->subscriptions);
    }
}