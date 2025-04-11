<?php

namespace Models;


class Monthly{

    private $inputs=["id","types_of_cars_id","plate","color","monthly_filiais_clientes_id","prisma","fk_color_id","fk_cartype_id"];
    public  $id;
    public  $types_of_cars_id;
    public  $plate;
    public  $color;
    public  $monthly_filiais_clientes_id;
    public  $prisma;
    public  $fk_color_id;
    public  $fk_cartype_id;


    public function __construct($data=null){
        if(!is_null($data) && is_array($data)){
            foreach($data as $key =>$row ){
                if(in_array($key,$this->inputs)){
                   $this->$key=$row;     
                }
            }
        }
    }


    
    public function toArray() {
        $data=[];
        foreach($this->inputs as $key => $input){
            $data[$input]=$this->$input;
        }

        return  $data;
    }
}


