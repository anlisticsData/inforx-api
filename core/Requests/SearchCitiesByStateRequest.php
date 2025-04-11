<?php

namespace Requests;

use PDO;
use Exception;
use Commons\Uteis;
use Resources\Strings;
use Commons\HttpRequests;
use Resources\HttpStatus;
use Interfaces\IRequestValidate;

class SearchCitiesByStateRequest implements IRequestValidate{
    private $inputs=["stateid"];
    public  $stateid;

    public function __construct($requesInputs=null){
        if(is_null($requesInputs)){
            $requesInputs = HttpRequests::Requests();
        }
        foreach($this->inputs as $key => $input){
            if(isset($requesInputs[$input])){
                $this->$input =$requesInputs[$input];
            }
        }
        $this->isValid();
    }

    function isValid()
    {
        if(Uteis::isNullOrEmpty($this->stateid)){
            throw new Exception(Strings::$STR_STATE_CODE_INVALIDE,HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }
    }
}