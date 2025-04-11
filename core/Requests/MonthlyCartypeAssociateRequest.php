<?php

namespace Requests;

use Exception;
use Commons\Uteis;
use Resources\Strings;
use Resources\HttpStatus;
use Interfaces\IRequestValidate;
 
 

class MonthlyCartypeAssociateRequest implements IRequestValidate{
    private $inputs=["monthly_id","fk_curtomers","fk_branch","plate","fk_typecar","fk_color","prisma"];
    public  $monthly_id;
    public  $fk_curtomers;
    public  $fk_branch;
    public  $plate;
    public  $fk_typecar;
    public  $fk_color;
    public  $prisma;
    public function __construct($data=null){
        if(!is_null($data) && is_array($data)){
            foreach($data as $key =>$row ){
                if(in_array($key,$this->inputs)){
                   $this->$key=$row;     
                }
            }
        }
        $this->isValid();
    }

    function isValid()
    {
        $errors=[];
        if(Uteis::isNullOrEmpty($this->monthly_id)){
            $errors[]=str_replace("[:input]","monthly_id",Strings::$STR_INPUTS_MANDATORY);
        }
        if(Uteis::isNullOrEmpty($this->plate)){
           $errors[]=str_replace("[:input]","placa",Strings::$STR_INPUTS_MANDATORY);
        }
        if( Uteis::isNullOrEmpty($this->fk_typecar) ){
            $errors[]=str_replace("[:input]","Tipo de Carro",Strings::$STR_INPUTS_MANDATORY);
        }

        if( Uteis::isNullOrEmpty($this->fk_color) ){
            $errors[]=str_replace("[:input]","Cor",Strings::$STR_INPUTS_MANDATORY);
        }
        if(count($errors)>0){
            throw new Exception(implode("[*]",$errors),HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }
        
    }


    function toArray()  {
        $rows=[];
        foreach($this->inputs as $key =>$value){
            $rows[$value]=$this->$value;
        }
        return $rows;
    }

}
