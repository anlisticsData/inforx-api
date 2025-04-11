<?php

namespace Dtos;
 
class BranchesServiceDto {
    private $inputs = ["id","description","price","created_at","status","execution_time_minutes","fk_customers"];

    public $id;
    public $description;
    public $price;
    public $created_at;
    public $status;
    
    public $execution_time_minutes;
    public $deleted;
    public $fk_customers;
 



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