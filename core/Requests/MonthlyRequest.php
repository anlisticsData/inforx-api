<?php

namespace Requests;

 
use Exception;
use Commons\Uteis;
use Resources\Strings;
use Resources\HttpStatus;
use Commons\ValidateInputs;
use Interfaces\IRequestValidate;


 


class MonthlyRequest implements IRequestValidate{
    private $inputs=["id","types_of_cars_id","plate","color","monthly_filiais_clientes_id"];
    public  $id;
    public  $types_of_cars_id;
    public  $plate;
    public  $color;
    public  $monthly_filiais_clientes_id;
    
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
        if(Uteis::isNullOrEmpty($this->types_of_cars_id)){
           $errors[]=str_replace("[:input]","types_of_cars_id",Strings::$STR_CAR_TYPE_CODE_NOT_FOUND);
        }
        if( Uteis::isNullOrEmpty($this->plate) ){
            $errors[]=str_replace("[:input]","plate",Strings::$STR_CAR_MONTHLY_PAYER_PLATE);
        }

        if( Uteis::isNullOrEmpty($this->color) ){
            $errors[]=str_replace("[:input]","color",Strings::$STR_BRANCHES_COSTUMER_INVALID);
        }

        if( Uteis::isNullOrEmpty($this->monthly_filiais_clientes_id) ){
            $errors[]=str_replace("[:input]","monthly_filiais_clientes_id",Strings::$STR_BRANCHES_COSTUMER_INVALID);
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