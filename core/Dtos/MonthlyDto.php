<?php

namespace Dtos;

use Commons\Uteis;


class MonthlyDto{


    private $inputs=["id","types_of_cars_id","plate","color","monthly_filiais_clientes_id"];
    public  $id;
    public  $types_of_cars_id;
    public  $plate;
    public  $color;
    public  $monthly_filiais_clientes_id;
    
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
