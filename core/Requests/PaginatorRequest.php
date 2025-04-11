<?php

namespace Requests;

use Interfaces\IRequestValidate;
use Commons\HttpRequests;


class PaginatorRequest implements IRequestValidate{
    private $inputs=["pager"];
    public  $pager=1;
    public function __construct($requesInputs=null){
        if(is_null($requesInputs)){
            $requesInputs = HttpRequests::Requests();
        }

        foreach($this->inputs as $key => $input){
            if(isset($requesInputs[$input])){
                $this->$input =$requesInputs[$input];
            }
        }
    }
    function isValid(){}
}