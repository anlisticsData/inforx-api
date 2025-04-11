<?php

namespace Requests;

 
use Exception;
use Commons\Uteis;
use Resources\Strings;
use Resources\HttpStatus;
use Interfaces\IRequestValidate;

class PaymentsExitCarRequest implements IRequestValidate{
    private $inputs=["uuid"];
    public  $uuid;
    public function __construct($requesInputs=null){
        
        foreach($this->inputs as $key => $input){
            if(isset($requesInputs[$input])){
                $this->$input =$requesInputs[$input];
            }
        }
        $this->isValid();
    }

    function isValid()
    {
        $errors=[];
    
        if(Uteis::isNullOrEmpty($this->uuid)){
            $errors[]=str_replace("[:input]","uuid",Strings::$STR_INPUTS_MANDATORY);
         }
       
         if(count($errors)>0){
            throw new Exception(implode("[*]",$errors),HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }
        
    }

    
    public function toArray() {
        $list=[];
        foreach($this->inputs as $key => $input){
            $list[$input]=$this->$input;
        }

        return  $list;
    }
    
}