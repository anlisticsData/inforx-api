<?php
namespace Models;

use Interfaces\IToArray;

class CarAssociation implements IToArray{
    private $inputs=["id","plate","model","brand","description","created_at","cancel_at"];
    public  $id;
    public $plate;
    public $model;
    public $brand;
    public $description;
    public $created_at;
    public $cancel_at;
    
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