<?php

namespace Models;


class Menu{

    private $inputs=["id","group_fk", "descricao", "actions", "ico","type", "submenus"];
    public $id;
    public $group_fk;
    public $descricao;
    public $actions;
    public $ico;
    public $type;
    public $submenus;
  
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


