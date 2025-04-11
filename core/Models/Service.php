<?php

namespace Models;

use Commons\Clock;


class Service{

    private $inputs=["id","description","price","created_at","status","execution_time_minutes","deleted_at","branches_id"];
    public $id;
    public $description;
    public $price;
    public $created_at;
    public $status;
    public $execution_time_minutes;
    public $deleted_at;
    public $customer_id;
    public function __construct($data=null){
        $this->status=1;
        $this->created_at = Clock::NowDate();
        if(!is_null($data) && is_array($data)){
            foreach($data as $key =>$row ){
                if(in_array($key,$this->inputs)){
                   $this->$key=$row;     
                }
            }
        }
    }
}


