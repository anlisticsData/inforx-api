<?php

namespace Requests;

use PDO;
use Exception;
use Commons\Uteis;
use Resources\Strings;
use Resources\HttpStatus;
use Interfaces\IRequestValidate;

class UserRequest implements IRequestValidate{
    private $inputs=["login","password"];
    public  $login;
    public  $password;


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
        if(Uteis::isNullOrEmpty($this->login) || Uteis::isNullOrEmpty($this->password)){
            throw new Exception(Strings::$STR_USER_INVALIDE,HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }
    }
}