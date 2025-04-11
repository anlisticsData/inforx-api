<?php

namespace Dtos;

use Commons\Uteis;
 
 

class MonthlyCartypeAssociateDto{
    private $inputs=["monthly_id","fk_curtomers","fk_branch","plate","fk_typecar","fk_color","prisma"];
    public  $monthly_id;
    public  $fk_curtomers;
    public  $fk_branch;
    public  $plate;
    public  $fk_typecar;
    public  $fk_color;
    public  $prisma;
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
