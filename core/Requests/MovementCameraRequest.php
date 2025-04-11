<?php

namespace Requests;

use Commons\Uteis;
use Resources\Strings;
use Resources\HttpStatus;
use Interfaces\IRequestValidate;
use TheSeer\Tokenizer\Exception;



class MovementCameraRequest implements IRequestValidate
{
    private $fields = ["id", "uuid", "nsr", "data", "hours", "processed", "sensor_code", "concierge", "plate", "created_at", "deleted_at","remote_ref"];
    public  $id;
    public  $uuid;
    public  $nsr;
    public  $data;
    public  $hours;
    public  $processed;
    public  $sensor_code;
    public  $concierge;
    public  $plate;
    public  $created_at;
    public  $deleted_at;
    public  $remote_ref=0;

    public function __construct($data = null)
    {
        if (!is_null($data) && is_array($data)) {
            foreach ($data as $key => $row) {
                if (in_array($key, $this->fields)) {
                    $this->$key = $row;
                }
            }
        }

        $this->isValid();
    }


    function isValid()
    {
        $errors = [];

        if (Uteis::isNullOrEmpty($this->plate)) {
            $errors[] = str_replace("[:input]", "placa", Strings::$STR_INPUTS_MANDATORY);
        }
        if (Uteis::isNullOrEmpty($this->sensor_code)) {
            $errors[] = str_replace("[:input]", "código do sensor", Strings::$STR_INPUTS_MANDATORY);
        }

        if (Uteis::isNullOrEmpty($this->concierge)) {
            $errors[] = str_replace("[:input]", "portaria do sensor", Strings::$STR_INPUTS_MANDATORY);
        }
        if (Uteis::isNullOrEmpty($this->data)) {
            $errors[] = str_replace("[:input]", "data", Strings::$STR_INPUTS_MANDATORY);
        }

        if (Uteis::isNullOrEmpty($this->hours)) {
            $errors[] = str_replace("[:input]", "horas", Strings::$STR_INPUTS_MANDATORY);
        }

        if (Uteis::isNullOrEmpty($this->remote_ref)) {
            $errors[] = str_replace("[:input]", "código de referencia remota", Strings::$STR_INPUTS_MANDATORY);
        }

        if (count($errors) > 0) {
            throw new Exception(implode("[*]", $errors), HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }
    }



    function toArray()
    {
        $rows = [];
        foreach ($this->fields as $key => $value) {
            $rows[$value] = $this->$value;
        }
        return $rows;
    }
}
