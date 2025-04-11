<?php

namespace Requests;

use Exception;
use Commons\Uteis;
use Resources\Strings;
use Resources\HttpStatus;
use Interfaces\IRequestValidate;


class PricesByIntervalsRequest implements IRequestValidate {
    private $inputs=["id","fk_princes_id","fk_branch_id","initial_start","initial_end","tolerence","mult","sum","price","deleted_at","created_at"];
    public $id;
    public $fk_princes_id;
    public $fk_branch_id;
    public $initial_start;
    public $initial_end;
    public $tolerence;
    public $mult;
    public $sum;
    public $price;
    public $deleted_at;
    public $created_at;
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
    function toArray()  
    {
        $rows = [];
        foreach ($this->inputs as $key => $value) {
            $rows[$value] = $this->$value;
        }
        return $rows;
    }


    function isValid()
    {
        $errors=[];
        if(Uteis::isNullOrEmpty($this->fk_princes_id)){
            $errors[]=Strings::$STR_BRANCH__INVALID;
        }
      

        if(Uteis::isNullOrEmpty($this->initial_start)){
            $errors[]=sprintf(Strings::$STR_INPUTS_INVALID,Strings::$APP_AGREEMENT_INPUT_START_INTERVAL);
        }

        if(Uteis::isNullOrEmpty($this->initial_end)){
            $errors[]=sprintf(Strings::$STR_INPUTS_INVALID,Strings::$APP_AGREEMENT_INPUT_END_INTERVAL);
        }

        if(Uteis::isNullOrEmpty($this->tolerence)){
            $errors[]=sprintf(Strings::$STR_INPUTS_INVALID,Strings::$APP_PRINCES_INPUT_TOLERENCE_INTERVAL);
        }

        if(Uteis::isNullOrEmpty($this->price)){
            $errors[]=sprintf(Strings::$STR_INPUTS_INVALID,Strings::$APP_PRINCES_INPUT_VALUE_INVALID);
        }


        if(!Uteis::validateScheduleHMS($this->initial_start) || !Uteis::validateScheduleHMS($this->initial_end)){
            $errors[]=sprintf(Strings::$STR_INPUTS_INVALID,Strings::$APP_AGREEMENT_INPUT_START_OR_END_INTERVAL);
        }


        if(Uteis::isFirstTimeGreater($this->initial_start,$this->initial_end) ){
            $errors[]=sprintf(Strings::$STR_INPUTS_INVALID,Strings::$APP_AGREEMENT_INPUT_END_MAIOR);
        }




        if(count($errors) > 0){
            throw new Exception(implode("\n",$errors),HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }
    }

}