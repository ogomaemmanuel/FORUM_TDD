<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;


class ThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_user_can_browse_threads()
    {
        $thread =factory('App\Thread')->create();
        $response = $this->get('/threads');
        $response->assertSee($thread->title);

    }
}