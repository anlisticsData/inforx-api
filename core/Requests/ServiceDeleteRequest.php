<?php

namespace Requests;

use Exception;
use Commons\Uteis;
use Resources\Strings;
use Commons\BaseRequest;
use Resources\HttpStatus;
use Interfaces\IRequestValidate;

 

 

class ServiceDeleteRequest extends BaseRequest implements IRequestValidate {
    private $inputs=["id","customer_id"];
    public  $id;
    public  $customer_id;
    
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
        if(Uteis::isNullOrEmpty($this->id)){
             throw new Exception(Strings::$STR_CODE_RECOVER_INVALID_NOT_EXIST,HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }

        if(Uteis::isNullOrEmpty($this->customer_id)){
            throw new Exception(Strings::$STR_SERVICE_BRANCHE_COSTUMER_NOT_FOUND,HttpStatus::$HTTP_CODE_BAD_REQUEST);
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