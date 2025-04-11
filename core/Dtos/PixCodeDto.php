<?php

namespace Dtos;

use Exception;
use Commons\Uteis;
use Resources\Strings;
use Interfaces\IToArray;
use Resources\HttpStatus;



class PixCodeDto  implements IToArray{
    private $inputs=["pix_code", "pix_key"];
    public  $pix_code;
    public  $pix_key;
    public function __construct($data=null){
        if(!is_null($data) && is_array($data)){
            foreach($data as $key =>$row ){
                if(in_array($key,$this->inputs)){
                   $this->$key=$row;     
                }
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