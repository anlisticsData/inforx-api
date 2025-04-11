<?php

namespace Models;


class Colors{

    private $inputs=["id","description","created_at"];
    public $id;
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
}
