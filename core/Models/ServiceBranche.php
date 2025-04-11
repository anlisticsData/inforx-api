<?php

namespace Models;

use Commons\Clock;
use Resources\APPLICATION;

class ServiceBranche{
    private $inputs=["id","
    ","price","status_service","deleted_at","services_id","branches_id"];
    public $id;
    public $created_at;
    public $price;
    public $status_service;
    public $deleted_at;
    public $branches_id;
    public $services_id;

    public function __construct($data=null){
        $this->status=APPLICATION::$APP_STATUS_NEW;
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


