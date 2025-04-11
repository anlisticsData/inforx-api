<?php

namespace Models;


class TokenVerify{

    private $inputs=["expired_time", "updated_at"];
    public $expired_time;
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


