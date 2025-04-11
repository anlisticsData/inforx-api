<?php

namespace Requests;

use Exception;
use Commons\Uteis;
use Resources\Strings;
use Commons\HttpRequests;
use Resources\HttpStatus;
use Interfaces\IRequestValidate;


class PaginatorRequestParams implements IRequestValidate{
    private $inputs=["pager","params"];
    public $params;
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
    function isValid(){
     
    }
}