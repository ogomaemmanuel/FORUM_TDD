<?php

use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseMigrations;


class FavoritesTest extends TestCase
{
    Use DatabaseMigrations;


    /** @test */
    function guest_can_not_favorite_anything(){
        $this->withExceptionHandling()

            ->post("replies/1/favorites")

            ->assertRedirect('/login');

    }


    /** @test */
    function an_authenticated_user_can_favorite_any_reply()
    {
        $this->signIn();

        $reply = create('App\Reply');

        $this->post('replies/'.$reply->id."/favorites");

        $this->assertCount(1,$reply->favorites);
    }

 /** @test */
    function an_authenticated_user_can_unfavorite_any_reply()
    {
        $this->signIn();

        $reply = create('App\Reply');


        $reply->favorite();

        $this->delete('replies/'.$reply->id."/favorites");

        $this->assertCount(0,$reply->favorites);
    }

    /** @test */
    function an_authenticated_user_may_only_favorite_a_reply_once()
    {
        $this->signIn();

        $reply = create('App\Reply');

        $this->post('replies/'.$reply->id."/favorites");

        $this->post('replies/'.$reply->id."/favorites");

        $this->assertCount(1,$reply->favorites);
    }
}