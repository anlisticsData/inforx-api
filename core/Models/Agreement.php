<?php

namespace Models;


class Agreement{
    private $inputs=["id","name","doc","description","created_at","address","start","end","prices","price","fk_branche_id"];
    public $id;
    public $name;
    public $doc;
    public $description;
    public $created_at;
    public $address;
    public $start;
    public $end;
    public $prices;
    public $price;
    public $fk_branche_id;

 

    public function __construct($data=null){
        if(!is_null($data) && is_array($data)){
            foreach($data as $key =>$row ){
                if(in_array($key,$this->inputs)){
                   $this->$key=$row;     
                }
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


