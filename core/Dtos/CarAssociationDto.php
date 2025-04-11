<?php
namespace Dtos;

use Interfaces\IToArray;

class CarAssociationDto  implements IToArray{
    private $inputs=["plate","model","brand","description","created_at"];
    public $plate;
    public $model;
    public $brand;
    public $description;
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
    function toArray()  {
        $rows=[];
        foreach($this->inputs as $key =>$value){
            $rows[$value]=$this->$value;
        }
        return $rows;
    }
}