<?php

namespace Models;


class State{

    private $inputs=["id","name","uf","created_at","updated_at"];
    public $id;
    public $name;
    public $uf;
    public $created_at;
    public $updated_at;
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


