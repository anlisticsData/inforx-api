<?php

namespace Models;


class Group{

    private $inputs=["id", "descriptions", "created_at", "deleted_at", "state"];
    public $id;
    public $descriptions;
    public $created_at;
    public $deleted_at;
    public $state;
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


