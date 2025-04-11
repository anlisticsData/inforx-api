<?php

namespace Models;


class Cartype{

    private $inputs=["id","model","brand"];
    public $id;
    public $model;
    public $brand;
 
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


