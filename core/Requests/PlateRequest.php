<?php

namespace Requests;

use PDO;
use Exception;
use Commons\Uteis;
use Resources\Strings;
use Resources\HttpStatus;
use Interfaces\IRequestValidate;
 
class PlateRequest implements IRequestValidate{
    private $inputs=["plate"];
    public  $plate;
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
        if(Uteis::isNullOrEmpty($this->plate)){
            throw new Exception(Strings::$STR_CODE_RECOVER_INVALID_NOT_EXIST,HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }
    }
}