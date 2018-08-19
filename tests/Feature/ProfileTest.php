<?php

use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseMigrations;


class ProfileTest extends TestCase
{
    Use DatabaseMigrations;
    /** @test */
    public function a_user_has_profile()
    {

        $user = create('App\User');
       $this->get("profiles/{$user->name}")
           ->assertSee($user->name);
    }
    /** @test */
    public function profile_display_all_threads_created_by_associated_user()
    {
        $user = create('App\User');

        $thread= create('App\Thread',['user_id'=>$user->id]);

        $this->get("profiles/{$user->name}")

            ->assertSee($thread->body)

            ->assertSee($thread->title);
    }
}