<?php

namespace Models;


class Setting{
    private $inputs = ["id","type","content"];
    public $id;
    public $type;
    public $content;
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


