<?php

namespace Models;


class Customer{

    private $inputs=["id","description","cnpj","address","state","created_at","deleted_at","login","password"];
    public  $id;
    public  $description;
    public  $cnpj;
    public  $address;
    public  $state;
    public  $login;
    public  $password;
    public  $created_at;
    public  $deleted_at;
    public  $group_id;
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


