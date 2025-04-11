<?php

namespace Requests;

use PDO;
use Exception;
use Commons\Uteis;
use Resources\Strings;
use Resources\HttpStatus;
use Interfaces\IRequestValidate;
 
class IsOpenCarRequest implements IRequestValidate{
    private $inputs=["plateOrCode","branche"];
    public  $plateOrCode;
    public $branche;
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
        if(Uteis::isNullOrEmpty($this->plateOrCode)){
            throw new Exception(str_replace("[:input]","plateOrCode",Strings::$STR_INPUTS_MANDATORY),HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }
        if(Uteis::isNullOrEmpty($this->branche)){
            throw new Exception(str_replace("[:input]","branche",Strings::$STR_INPUTS_MANDATORY),HttpStatus::$HTTP_CODE_BAD_REQUEST);

        }

    }
}