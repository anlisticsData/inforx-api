<?php

namespace Requests;

use Exception;
use Commons\Uteis;
 
use Resources\Strings;
use Commons\BaseRequest;
use Resources\HttpStatus;
use Interfaces\IRequestValidate;

 

class ServiceBrancheRequest extends BaseRequest implements IRequestValidate {
    private $inputs=["code_branche", "code_service"];
    public  $code_branche;
    public  $code_service;
   
    public function __construct($requesInputs){
        foreach($this->inputs as $key => $input){
            if(isset($requesInputs[$input])){
                $this->$input =$requesInputs[$input];
            }
        }
       $this->isValid();
    }

    function isValid()
    {
        if(Uteis::isNullOrEmpty($this->code_branche)){
             throw new Exception(Strings::$STR_SERVICE_BRANCHE_COSTUMER_NOT_FOUND,HttpStatus::$HTTP_CODE_FOUND);
        }

        if(Uteis::isNullOrEmpty($this->code_service)){
            throw new Exception(Strings::$STR_BRANCHES_COSTUMER_NOT_FOUND,HttpStatus::$HTTP_CODE_FOUND);
        }


       
        
    }


    public function toArray() {
        $data=[];
        foreach($this->inputs as $key => $input){
            $data[$input]=$this->$input;
        }

        return  $data;
    }
}