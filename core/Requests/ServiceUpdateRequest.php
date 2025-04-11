<?php

namespace Requests;

use Exception;
use Commons\Uteis;
 
use Resources\Strings;
use Commons\BaseRequest;
use Resources\HttpStatus;
use Interfaces\IRequestValidate;

 

class ServiceUpdateRequest extends BaseRequest implements IRequestValidate {
    private $inputs=["description", "price","execution_time_minutes","customer_id","id"];
    public  $description;
    public  $price;
    public  $execution_time_minutes;
    public  $customer_id;
    public  $id;
    
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
        if(Uteis::isNullOrEmpty($this->description)){
             throw new Exception(Strings::$STR_STATE_CODE_INVALIDE,HttpStatus::$HTTP_CODE_FOUND);
        }

        if(Uteis::isNullOrEmpty($this->price)){
            throw new Exception(Strings::$STR_STATE_CODE_INVALIDE,HttpStatus::$HTTP_CODE_FOUND);
        }


        if(Uteis::isNullOrEmpty($this->execution_time_minutes)){
            throw new Exception(Strings::$STR_STATE_CODE_INVALIDE,HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }

        if(Uteis::isNullOrEmpty($this->customer_id)){
            throw new Exception(Strings::$STR_SERVICE_BRANCHE_COSTUMER_NOT_FOUND,HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }

        if(Uteis::isNullOrEmpty($this->id)){
            throw new Exception(Strings::$STR_BRANCHES_COSTUMER_NOT_FOUND,HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
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