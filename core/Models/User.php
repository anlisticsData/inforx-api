<?php

namespace Models;


class User{

    private $inputs=["id","name","email","password","state","created_at","updated_at","groups_id","avatar_id","branches_id","customer_id","settings"];
    public $id;
    public $name;
    public $email;
    public $password;
    public $state;
    public $created_at;
    public $avatar_id;
    public $branches_id;
    public $customer_id;
    public $groups_id;
    public $updated_at;
    public $settings;
    
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


