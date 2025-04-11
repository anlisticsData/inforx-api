<?php

namespace Requests;

use Exception;
use Commons\Uteis;
use Resources\Strings;
use Commons\BaseRequest;
use Resources\HttpStatus;
use Interfaces\IRequestValidate;



class PixCodeRequest extends BaseRequest implements IRequestValidate {
    private $inputs=["pix_code", "pix_key"];
    public  $pix_code;
    public  $pix_key;
   

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
        if(Uteis::isNullOrEmpty($this->pix_code) || Uteis::isNullOrEmpty($this->pix_key)){
             throw new Exception(Strings::$STR_SETTINGS_PIX_INVALID,HttpStatus::$HTTP_CODE_NOT_FOUND);
        }
       
    }

    public function toArray() {
        $list=[];
        foreach($this->inputs as $key => $input){
            $list[$input]=$this->$input;
        }

        return  $list;
    }
}