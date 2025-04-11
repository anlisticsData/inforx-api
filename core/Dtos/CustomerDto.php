<?php

namespace Dtos;

use Commons\Uteis;


class CustomerDto{

    private $inputs=["id","description","cnpj","address","state","created_at","deleted_at","email","password","group_id"];
    public  $id;
    public  $description;
    public  $cnpj;
    public  $address;
    public  $state;
    public  $email;
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
