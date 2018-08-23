<?php

use App\Spam;
use Tests\TestCase;




class SpamTest extends TestCase
{

    /** @test */
    public function it_validates_spam()
    {

        $spam = new Spam;



        $this->assertFalse( $spam->detect("Innocent Reply here"));

        $this->expectException(\Exception::class);

        $spam->detect("yahoo customer support");
        
    }
}