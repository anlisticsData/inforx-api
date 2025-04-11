<?php
namespace Dtos;

use Interfaces\IToArray;

class PaymentOfMonthlyPaymentsDto implements IToArray{
    private $inputs=["id","fk_monthly_payer","created_at","payment_made_on","payment_date","state"];
    public  $id;
    public $fk_monthly_payer;
    public $created_at;
    public $payment_made_on;
    public $payment_date;
    public $state;
     
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