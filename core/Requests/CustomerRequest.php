<?php

namespace Requests;

 
use Exception;
use Commons\Uteis;
use Resources\Strings;
use Resources\HttpStatus;
use Commons\ValidateInputs;
use Interfaces\IRequestValidate;

class CustomerRequest implements IRequestValidate{
    private $inputs=["id","description","cnpj","address","state","created_at","deleted_at","email","password"];
    public  $id;
    public  $description;
    public  $cnpj;
    public  $address;
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
        if(Uteis::isNullOrEmpty($this->description)){
           $errors[]=str_replace("[:input]","description",Strings::$STR_INPUTS_MANDATORY);
        }
        if( Uteis::isNullOrEmpty($this->cnpj) ){
            $errors[]=str_replace("[:input]","cnpj",Strings::$STR_INPUTS_MANDATORY);
        }

        if(!ValidateInputs::validateCnpjOrCpf($this->cnpj) ){
            $errors[]=str_replace("[:input]","cnpj/cpf",Strings::$STR_INPUTS_INVALID_FORMAT);
        }
        if(Uteis::isNullOrEmpty($this->email)){
            $errors[]=str_replace("[:input]","email",Strings::$STR_INPUTS_MANDATORY);
         }
         if( Uteis::isNullOrEmpty($this->password) ){
             $errors[]=str_replace("[:input]","password",Strings::$STR_INPUTS_MANDATORY);
         }
         if( Uteis::isNullOrEmpty($this->address) ){
            $errors[]=str_replace("[:input]","address",Strings::$STR_INPUTS_MANDATORY);
         }
        if(count($errors)>0){
            throw new Exception(implode("[*]",$errors),HttpStatus::$HTTP_CODE_BAD_REQUEST);
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