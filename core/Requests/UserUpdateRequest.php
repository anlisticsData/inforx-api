<?php

namespace Requests;

use Commons\Uteis;
use Resources\Strings;
use Resources\HttpStatus;
use Exception;



class UserUpdateRequest{
    private $inputs=["name","password","is_update_password","code_user"];
    public  $name;
    public  $password;
    public  $is_update_password;
    public  $code_user;


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
        if(Uteis::isNullOrEmpty($this->name)==true){
            throw new Exception(Strings::$STR_USER_INVALIDE,HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }
        if($this->is_update_password==true && Uteis::isNullOrEmpty($this->password)==true){
            throw new Exception(Strings::$STR_USER_INVALIDE,HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }


    }
}