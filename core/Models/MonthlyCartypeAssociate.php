<?php

namespace Models;

use Commons\Uteis;
 
 
class MonthlyCartypeAssociate{
    private $inputs=["uuid","fk_monthy_players_id","fk_car","created_at","cancel_at"];
    public  $uuid;
    public  $fk_monthy_players_id;
    public  $fk_car;
    public  $created_at;
    public  $cancel_at;



    public function generateUuid(){
        $this->uuid =  uniqid();
    }
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
