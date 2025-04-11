<?php

namespace Dtos;


class CityDto{

    private $inputs=["id","name","state_id","created_at","updated_at"];
    public $id;
    public $name;
    public $state_id;
    public $created_at;
    public $updated_at;
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
