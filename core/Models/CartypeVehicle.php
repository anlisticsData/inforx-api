<?php

namespace Models;


class CartypeVehicle{

    private $inputs=["id","fk_price_id","name","created_at"];
    public $id;
    public $fk_price_id;
    public $name;
    public $created_at;
 
    public function __construct($data=null){
        if(!is_null($data) && is_array($data)){
            foreach($data as $key =>$row ){
                if(in_array($key,$this->inputs)){
                   $this->$key=$row;     
                }
            }
        }
    }
}


