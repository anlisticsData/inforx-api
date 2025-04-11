<?php

namespace Models;


class PricesIntervals{
    private $inputs=["id","fk_princes_id","fk_branch_id","initial_start","initial_end","tolerence","mult","sum","price","deleted_at","created_at"];
    public $id;
    public $fk_princes_id;
    public $fk_branch_id;
    public $initial_start;
    public $initial_end;
    public $tolerence;
    public $mult;
    public $sum;
    public $price;
    public $deleted_at;
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
    function toArray()  
    {
        $rows = [];
        foreach ($this->inputs as $key => $value) {
            $rows[$value] = $this->$value;
        }
        return $rows;
    }
}


