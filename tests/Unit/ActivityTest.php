<?php

use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseMigrations;


class ActivityTest extends TestCase
{
    Use DatabaseMigrations;

    /** @test */
    public function records_activity_when_a_thread_is_created()
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

    }
}