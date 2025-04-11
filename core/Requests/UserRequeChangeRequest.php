<?php

namespace Requests;

use PDO;
use Exception;
use Commons\Uteis;
use Resources\Strings;
use Resources\HttpStatus;
use Interfaces\IRequestValidate;

class UserRequeChangeRequest  implements IRequestValidate{
    private $inputs=["current_password","new_password"];
    public  $current_password;
    public $new_password;



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
        if(Uteis::isNullOrEmpty($this->new_password) || Uteis::isNullOrEmpty($this->current_password)){
            throw new Exception(Strings::$STR_USER_INVALIDE_PASSWORD,HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }
    }

    function toArray()  {
        $rows=[];
        foreach($this->inputs as $key =>$value){
            $rows[$value]=$this->$value;
        }
        return $rows;
    }

}