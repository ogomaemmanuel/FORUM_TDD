<?php
/**
 * Created by PhpStorm.
 * User: eogoma
 * Date: 8/12/18
 * Time: 7:29 PM
 */

namespace App\Filters;


use App\User;
use Illuminate\Http\Request;

abstract class Filters
{
    /**
     * @var Request
     */
    protected $request,$builder;

    protected $filters=[];

    /**
     * ThreadFilters constructor.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($builder)
    {
        $this->builder = $builder;


        foreach ($this->getFilters() as $filter=> $value){

            if( method_exists($this, $filter)) {
                $this->$filter($value);
            };



        }
        return $builder;


    }

    public function getFilters(){
        return $this->request->intersect($this->filters);
    }


}