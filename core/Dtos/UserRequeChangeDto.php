<?php

namespace Dtos;

use Exception;
use Commons\Uteis;
use Resources\Strings;
use Resources\HttpStatus;
use Interfaces\IRequestValidate;
use Interfaces\IToArray;

class UserRequeChangeDto  implements IToArray{
    private $inputs=["current_password","new_password"];
    public  $current_password;
    public $new_password;
    public function __construct($requesInputs){
        foreach($this->inputs as $key => $input){
            if(isset($requesInputs[$input])){
                $this->$input =$requesInputs[$input];
            }
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