<?php

namespace Models;

use Interfaces\IToArray;

class PaymentType implements IToArray{
    private $inputs = ["id","payment_methods_name","payment_methods_active","created_at","deleted_at","fk_branches_id","ord","default"];
    public $id;
    public $payment_methods_name;
    public $payment_methods_active;
    public $created_at;
    public $deleted_at;
     public $fk_branches_id;
     public $ord;
     public $default;
         
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


