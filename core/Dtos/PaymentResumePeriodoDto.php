<?php

namespace Dtos;

use Interfaces\IToArray;

class PaymentResumePeriodoDto implements IToArray{
  


    private $inputs = [
        "id", 
        "created_at", 
        "fk_branch_id", 
        "ip_address", 
        "fk_user_id", 
        "fk_payment_types", 
        "value_payment_types", 
        "fk_pricing_id", 
        "fk_movements_id", 
        "uuid_id_plate_direction_create", 
        "receipt_by_box", 
        "payment_change",
        "total",
        "hours",
        "minutes",
        "fk_agreements_discont",
        "value_agreements_discont",
        "discount_applied",
        "fk_agreement_id"
    ];

    public $id;
    public $created_at;
    public $fk_branch_id;
    public $ip_address;
    public $fk_user_id;
    public $fk_payment_types;
    public $fk_pricing_id;
    public $fk_movements_id;
    public $uuid_id_plate_direction_create;
    public $receipt_by_box;
    public $payment_change;
    public $total;
    public $hours;
    public $minutes;
    public $fk_agreements_discont;
    public $discount_applied;
    public $value_payment_types;
    public $value_agreements_discont;
    public $fk_agreement_id;
    



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


