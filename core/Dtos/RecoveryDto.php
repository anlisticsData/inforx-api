<?php

namespace Dtos;
 

class RecoveryDto{

    private $inputs=["id","user_code","validate_at","created_at"];
    public $id;
    public $user_code;
    public $validate_at;
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
