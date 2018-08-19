<?php

use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseMigrations;


class ActivityTest extends TestCase
{
    Use DatabaseMigrations;

    /** @test */
    public function it_records_activity_when_a_thread_is_created()
    {

        $this->signIn();

        $thread=create('App\Thread');

        $this->assertDatabaseHas("activities",
            [
                "type" => 'created_thread',
                "user_id"=>auth()->id(),
                "subject_id"=>$thread->id,
                "subject_type"=>"App\Thread"
            ]);

        $activity = \App\Activity::first();

        $this->assertEquals($activity->subject_id,$thread->id);

    }
    /** @test */
    public function it_records_activity_when_a_reply_is_created()
    {

        $this->signIn();

        $reply=create('App\Reply');


        $activity = \App\Activity::first();

        $this->assertEquals(2,\App\Activity::count());

    }
}