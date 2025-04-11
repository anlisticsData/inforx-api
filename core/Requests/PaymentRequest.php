<?php

namespace Requests;

use Exception;
use Commons\Uteis;
use Resources\Strings;
use Resources\HttpStatus;
use Interfaces\IRequestValidate;

class PaymentRequest implements IRequestValidate 
{
    private $inputs = [
        "id",
        "created_at",
        "fk_branch_id",
        "ip_address",
        "fk_user_id",
        "fk_payment_types",
        "fk_pricing_id",
        "fk_movements_id",
        "uuid_id_plate_direction_create",
        "receipt_by_box",
        "payment_change",
        "fk_agreements_discont"
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
    public $fk_agreements_discont = -1;

    public function __construct($requestInputs)
    {
        foreach ($this->inputs as $input) {
            if (isset($requestInputs[$input])) {
                $this->$input = $requestInputs[$input];
            }
        }
        $this->isValid();
    }

    public function isValid()
    {
        
        if (Uteis::isNullOrEmpty($this->fk_payment_types)) {
            throw new Exception(
                str_replace("[:input]", "fk_payment_types", Strings::$STR_INPUTS_INVALID_FORMAT),
                HttpStatus::$HTTP_CODE_BAD_REQUEST
            );
        }

        if (Uteis::isNullOrEmpty($this->fk_pricing_id)) {
            throw new Exception(
                str_replace("[:input]", "fk_pricing_id", Strings::$STR_INPUTS_INVALID_FORMAT),
                HttpStatus::$HTTP_CODE_BAD_REQUEST
            );
        }

        if (Uteis::isNullOrEmpty($this->uuid_id_plate_direction_create)) {
            throw new Exception(
                str_replace("[:input]", "uuid_id_plate_direction_create", Strings::$STR_INPUTS_INVALID_FORMAT),
                HttpStatus::$HTTP_CODE_BAD_REQUEST
            );
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
