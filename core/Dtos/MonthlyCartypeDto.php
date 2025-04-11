<?php

namespace Dtos;

use Commons\Uteis;
 
 

class MonthlyCartypeDto{

    private $inputs=["cartypeId","model","brand","color","plate","monthlyId","created_at","description","cnpj","group_id"];
    public  $cartypeId;
    public  $model;
    public  $brand;
    public  $color;
    public  $plate;
    public  $monthlyId;
    public  $created_at;
    public  $description;
    public  $cnpj;
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
