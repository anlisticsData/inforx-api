<?php

namespace Requests;

use PDO;
use Exception;
use Commons\Uteis;
use Resources\Strings;
use Resources\HttpStatus;
use Interfaces\IRequestValidate;

class UserRecoverRequest implements IRequestValidate{
    private $inputs=["email","code","newpassword"];
    public  $email;
    public  $code;
    public  $newpassword;
    
    
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
        if(Uteis::isNullOrEmpty($this->code) || Uteis::isNullOrEmpty($this->email)){
            throw new Exception(Strings::$STR_USER_INVALIDE_RECOVER_CODE_OR_EMAIL,HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }

        if(Uteis::isNullOrEmpty($this->newpassword)){
            throw new Exception(Strings::$STR_USER_INVALIDE_RECOVER_PASSWORD_USER_CODE,HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }


        
    }
}