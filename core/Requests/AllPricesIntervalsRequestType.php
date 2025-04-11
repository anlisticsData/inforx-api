<?php

namespace Requests;

use Exception;
use Commons\Uteis;
use Resources\Strings;
use Resources\HttpStatus;
use Interfaces\IRequestValidate;


class AllPricesIntervalsRequestType implements IRequestValidate {
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
    public function __construct($data=null,$fkBranchId){

      
        if(!is_null($data) && is_array($data)){
            foreach($data as $key =>$row ){
                if(in_array($key,$this->inputs)){
                   $this->$key=$row;     
                }
            }
        }

        $this->fk_branch_id=$fkBranchId;

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
            $errors[]=Strings::$APP_PRINCES_TYPES_ERROR;
        }
       
        if(Uteis::isNullOrEmpty($this->fk_branch_id)){
            $errors[]=Strings::$STR_BRANCH__INVALID;
        }


        
        if(count($errors) > 0){
            throw new Exception(implode("\n",$errors),HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }
    }

}