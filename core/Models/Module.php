<?php

namespace Models;

use Interfaces\IToArray;
 
class Module  implements IToArray {
    private $inputs = ["id","module", "description", "state", "ip_server", "user_server", "port_server", "user_db", "passw_db"];
    public $id;
    public $module;
    public $description;
    public $state;
    public $ip_server;
    public $user_server;
    public $port_server;
    public $user_db;
    public $passw_db;
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