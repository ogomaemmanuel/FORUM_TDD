<?php

namespace App\Inspections;

use Exception;

class InvalidKeywords{


protected  $invalidKeywords = [

'yahoo customer support'

];
    public function detect($body){


        foreach ($this->invalidKeywords as $keyword){
            if( stripos($body,$keyword)!==false){

                throw new Exception("Your Reply Contains Spam.");
            }
        }
    }

}
