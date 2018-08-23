<?php

namespace App;

class Spam
{

    public function detect($body)
    {

        //Detect Invalid keywords

        $this->detectInvalidKeywords();

        return false;

    }

    protected function detectInvalidKeywords()
    {
        $invalidKeywords = [

              'yahoo customer support'

        ];

        foreach ($invalidKeywords as $keyword){
            if( stripos(request("body"),'yahoo customer support')!==false){
                throw new \Exception("Your Reply Contains Spam.");
            }
        }



    }
}