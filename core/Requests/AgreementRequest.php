<?php

namespace Requests;

 
use Exception;
use Commons\Uteis;
use Resources\Strings;
use Resources\HttpStatus;
use Commons\ValidateInputs;
use Interfaces\IRequestValidate;

 



class AgreementRequest implements IRequestValidate{
    private $inputs=["id","name","doc","description","created_at","address","fk_branche_id","start","end","prices"];
    public $id;
    public $name;
    public $doc;
    public $description;
    public $created_at;
    public $address;
    public $fk_branche_id;
    public $start;
    public $end;
    public $prices;





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
        if(Uteis::isNullOrEmpty($this->fk_branche_id)){
            $errors[]=Strings::$STR_BRANCH__INVALID;
         
        }
        if(Uteis::isNullOrEmpty($this->name)){
            $errors[]=sprintf(Strings::$STR_INPUTS_INVALID,Strings::$APP_AGREEMENT_INPUT_NAME);
        }

        if(Uteis::isNullOrEmpty($this->start)){
            $errors[]=sprintf(Strings::$STR_INPUTS_INVALID,Strings::$APP_AGREEMENT_INPUT_START_INTERVAL);
        }

        if(Uteis::isNullOrEmpty($this->end)){
            $errors[]=sprintf(Strings::$STR_INPUTS_INVALID,Strings::$APP_AGREEMENT_INPUT_END_INTERVAL);
        }

        if(!Uteis::validateSchedule($this->start) || !Uteis::validateSchedule($this->end)){
            $errors[]=sprintf(Strings::$STR_INPUTS_INVALID,Strings::$APP_AGREEMENT_INPUT_START_OR_END_INTERVAL);
        }


        if(Uteis::isFirstTimeGreater($this->start,$this->end) ){
            $errors[]=sprintf(Strings::$STR_INPUTS_INVALID,Strings::$APP_AGREEMENT_INPUT_END_MAIOR);
        }


        if(count($errors) > 0){
            throw new Exception(implode("\n",$errors),HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }
 
        
    }


    function toArray()  
    {
        $rows = [];
        foreach ($this->inputs as $key => $value) {
            $rows[$value] = $this->$value;
        }
        return $rows;
    }

}