<?php

namespace Requests;

use Exception;
use Commons\Uteis;
use Resources\Strings;
use Resources\HttpStatus;
use Commons\ValidateInputs;
use Interfaces\IRequestValidate;

class CarRequest implements IRequestValidate{
    private $inputs=["plate","fk_typecar","fk_color","prisma","fk_type_of_vehicle","type_print"];
    public  $plate;
    public  $fk_typecar;
    public  $fk_color;
    public  $prisma;
    public  $fk_type_of_vehicle;
    public  $type_print=0;
    
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
        if(Uteis::isNullOrEmpty($this->plate)){
           $errors[]=str_replace("[:input]","prisma",Strings::$STR_INPUTS_MANDATORY);
        }
        if( Uteis::isNullOrEmpty($this->fk_typecar) ){
            $errors[]=str_replace("[:input]","fk_typecar",Strings::$STR_INPUTS_MANDATORY);
        }

        if( Uteis::isNullOrEmpty($this->fk_color) ){
            $errors[]=str_replace("[:input]","fk_color",Strings::$STR_INPUTS_MANDATORY);
        }

        if( Uteis::isNullOrEmpty($this->fk_type_of_vehicle) ){
            $errors[]=str_replace("[:input]","fk_type_of_vehicle",Strings::$STR_INPUTS_MANDATORY);
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