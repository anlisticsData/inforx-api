<?php

namespace Requests;

use Exception;
use Commons\Uteis;
use Resources\Strings;
use Commons\BaseRequest;
use Resources\HttpStatus;
use Interfaces\IRequestValidate;



class TypeCarRequest extends BaseRequest implements IRequestValidate {
    private $inputs=["id", "model","brand"];
    public  $id;
    public  $model;
    public  $brand;

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
        if(Uteis::isNullOrEmpty($this->model)){
             throw new Exception(Strings::$STR_CAR_TYPE_MODEL_INVALID,HttpStatus::$HTTP_CODE_NOT_FOUND);
        }

        if(Uteis::isNullOrEmpty($this->brand)){
            throw new Exception(Strings::$STR_CAR_TYPE_BRAND_INVALID,HttpStatus::$HTTP_CODE_NOT_FOUND);
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