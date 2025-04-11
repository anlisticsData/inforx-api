<?php

namespace Requests;

use Exception;
use Commons\Uteis;
use Resources\Strings;
use Resources\HttpStatus;
use Interfaces\IRequestValidate;

class MonthlyPayerRequest  implements IRequestValidate{

    private $inputs = [
        "id","userId","name", "email", "dt_nasc", "rg", "cpf", "phone", "phone_movel", 
        "address", "cep", "number", "complement", "city", "day_payment", 
        "used_vacancies", "update", "obs","type_of_monthly_fee","monthly_fee_model","monthly_fee_amount"
    ];
    public $userId;
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
    public $id;
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

        $this->isValid();
    }

    
    function isValid()
    {
        if(Uteis::isNullOrEmpty($this->name) || Uteis::isNullOrEmpty($this->email)){
            throw new Exception(Strings::$STR_NOT_VALIDATE_MONTHLY_PAYERS,HttpStatus::$HTTP_CODE_BAD_REQUEST);
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
