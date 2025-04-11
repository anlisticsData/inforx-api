<?php

namespace Requests;

use Exception;
use Commons\Uteis;
use Resources\Strings;
use Resources\HttpStatus;
use Interfaces\IRequestValidate;


class BoxRequest implements IRequestValidate
{
    private $inputs = ["id", "type", "date", "amount", "created_at", "users_id", "branches_id"];
    public $id;
    public $type;
    public $date;
    public $amount;
    public $created_at;
    public $users_id;
    public $branches_id;

    public function __construct($data = null)
    {
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
        if (Uteis::isNullOrEmpty($this->amount)) {

            throw new Exception(str_replace("[:input]", "amount", Strings::$STR_INPUTS_MANDATORY), HttpStatus::$HTTP_CODE_BAD_REQUEST);
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
