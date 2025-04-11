<?php

namespace Requests;

use Commons\Uteis;
use Exception;
use Interfaces\IRequestValidate;
use Resources\HttpStatus;
use Resources\Strings;

class SearchBranchesByRequest implements IRequestValidate {
    private $inputs = ["id"];
    public $id;

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
        if(Uteis::isNullOrEmpty($this->id)){
            throw new Exception(str_replace("[:input]","id",Strings::$STR_BRANCHES_COSTUMER_INVALID),HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }
    }
}