<?php

namespace Requests;

use PDO;
use Exception;
use Commons\Uteis;
use Resources\Strings;
use Resources\HttpStatus;
use Interfaces\IRequestValidate;

class HasNewPlateInput implements IRequestValidate{
    private $inputs=["module","customer","branch"];
    public  $module;
    public  $customer;
    public  $branch;


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
        if(Uteis::isNullOrEmpty($this->module) || Uteis::isNullOrEmpty($this->customer) || Uteis::isNullOrEmpty($this->branch)){
            throw new Exception(Strings::$STR_NOT_VALIDATE_MODULE,HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }
    }
}