<?php

namespace Models;


class Permission{

    private $inputs=["permissions_id", "groups_id", "uuid", "id", "router", "created_at", "state"];
    public $id;
    public $permissions_id;
    public $groups_id;
    public $uuid;
    public $router;
    public $created_at;
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


