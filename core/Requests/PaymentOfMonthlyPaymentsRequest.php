<?php
namespace Requests;

use Exception;
use Commons\Uteis;
use Resources\Strings;
use Interfaces\IToArray;
use Resources\HttpStatus;
use Interfaces\IRequestValidate;

class PaymentOfMonthlyPaymentsRequest implements IToArray,IRequestValidate{
    private $inputs=["id","fk_monthly_payer","created_at","payment_made_on","payment_date","state","payment_value"];
    public $id;
    public $fk_monthly_payer;
    public $created_at;
    public $payment_made_on;
    public $payment_date;
    public $state;
    public $payment_value; 

    public function __construct($data=null){
        if(!is_null($data) && is_array($data)){
            foreach($data as $key =>$row ){
                if(in_array($key,$this->inputs)){
                   $this->$key=$row;     
                }
            }
        }

        $this->isValid();
    }

    function isValid(){
        if(Uteis::isNullOrEmpty($this->fk_monthly_payer)){
            throw new Exception(str_replace("[:input]","fk_monthly_payer",Strings::$STR_BRANCHES_COSTUMER_INVALID),HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }
        if(Uteis::isNullOrEmpty($this->payment_value)){
            throw new Exception(str_replace("[:input]","payment_value",Strings::$STR_BRANCHES_COSTUMER_INVALID),HttpStatus::$HTTP_CODE_BAD_REQUEST);
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