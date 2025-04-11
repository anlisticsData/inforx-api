<?php

namespace Dtos;


class UserDto{

    private $inputs=["id","name","email","password","state","created_at","updated_at","groups_id",
    "avatar_id","acess","customer_id","branches_id","branche","menus","settings","customer_info"];
    public $id;
    public $name;
    public $email;
    public $password;
    public $state;
    public $created_at;
    public $avatar_id;
    public $groups_id;
    public $updated_at;
    public $group;
    public $permissions;
    public $token;
    public $avatar;
    public $branches_id;
    public $branche;
    public $customer_id;
    public $customer_info;
    public $menus;
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