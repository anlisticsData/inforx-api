<?php

namespace Models;

use Commons\Uteis;
use Interfaces\IToArray;

class MonthlyPayer implements IToArray{

    private $inputs = [
        "id","userId","fk_curtomers","fk_branch","name", "email", "dt_nasc", "rg", "cpf", "phone", "phone_movel", 
        "address", "cep", "number", "complement", "city", "day_payment", 
        "used_vacancies", "update", "obs","created_at","type_of_monthly_fee","monthly_fee_model","monthly_fee_amount"
    ];
    public $id;
    public $userId;
    public $fk_curtomers;
    public $fk_branch;
    public $name;
    public $email;
    public $dt_nasc;
    public $rg;
    public $cpf;
    public $phone;
    public $phone_movel;
    public $address;
    public $cep;
    public $number;
    public $complement;
    public $city;
    public $day_payment;
    public $used_vacancies;
    public $update;
    public $obs;
    public $created_at;
    public $type_of_monthly_fee;
    public $monthly_fee_model;
    public $monthly_fee_amount;
    



    public function __construct($data = null) {

         
        if (!is_null($data) && is_array($data)) {
            foreach ($data as $key => $row) {
                if (in_array($key, $this->inputs)) {
                    $this->$key = $row;
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
