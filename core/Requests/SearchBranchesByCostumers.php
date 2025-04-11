<?php

namespace Requests;

use Commons\Uteis;
use Exception;
use Interfaces\IRequestValidate;
use Resources\HttpStatus;
use Resources\Strings;

class SearchBranchesByCostumers implements IRequestValidate {
    private $inputs = ["costumerid"];
    public $costumerid;

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
        if(Uteis::isNullOrEmpty($this->costumerid)){
            throw new Exception(str_replace("[:input]","costumerid",Strings::$STR_BRANCHES_COSTUMER_INVALID),HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }
    }
}