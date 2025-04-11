<?php

namespace Requests;

use PDO;
use Exception;
use Commons\Uteis;
use Resources\Strings;
use Resources\HttpStatus;
use Interfaces\IRequestValidate;

class UserRecoverPasswordRequest implements IRequestValidate{
    private $inputs=["name","email"];
    public  $name;
    public  $email;


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
        if(Uteis::isNullOrEmpty($this->name) || Uteis::isNullOrEmpty($this->email)){
            throw new Exception(Strings::$STR_USER_INVALIDE_RECOVER_PASSWORD,HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }

        
    }
}