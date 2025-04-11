<?php

namespace Dtos;


class AgreementDto{
    private $inputs=["id","name","doc","description","created_at","address","fk_branche_id","start","end","prices"];
    public $id;
    public $name;
    public $doc;
    public $description;
    public $created_at;
    public $address;
    public $start;
    public $end;
    public $prices;
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


