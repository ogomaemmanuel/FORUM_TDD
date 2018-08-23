<?php

use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseMigrations;


class ChannelTest extends TestCase
{
    Use DatabaseMigrations;

    /** @test */
    public function a_channel_consists_of_threds()
    {
        $channel=create("App\Channel");

        $thread= create('App\Thread', ['channel_id'=>$channel->id]);

        $this->assertTrue($channel->threads->contains($thread));
    }
}