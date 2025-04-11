<?php

namespace Models;


use PDO;
use Exception;
use Commons\Uteis;
use Resources\Strings;
use Interfaces\IToArray;
use Resources\HttpStatus;
 
 
class IsOpenCar implements IToArray{
    private $inputs=["plateOrCode","branche"];
    public  $plateOrCode;
    public $branche;
    public function __construct($requesInputs){
        foreach($this->inputs as $key => $input){
            if(isset($requesInputs[$input])){
                $this->$input =$requesInputs[$input];
            }
        }
         
    }
    function toArray()  
    {
        $rows = [];
        foreach ($this->inputs as $key => $value) {
            $rows[$value] = $this->$value;
        }
        return $rows;
    }
}