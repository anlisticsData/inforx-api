<?php

namespace Requests;

 
use Exception;
use Commons\Uteis;
use Resources\Strings;
use Resources\HttpStatus;
use Interfaces\IRequestValidate;

class OperatorRequest implements IRequestValidate{
    private $inputs=["id","name","state","created_at","deleted_at","email","password"];
    public  $id;
    public  $state;
    public  $email;
    public  $password;
    public  $created_at;
    public  $deleted_at;
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
    
        if(Uteis::isNullOrEmpty($this->email)){
            $errors[]=str_replace("[:input]","email",Strings::$STR_INPUTS_MANDATORY);
         }
         if( Uteis::isNullOrEmpty($this->password) ){
             $errors[]=str_replace("[:input]","password",Strings::$STR_INPUTS_MANDATORY);
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