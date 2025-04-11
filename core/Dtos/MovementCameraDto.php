<?php

namespace Dtos;

use Commons\Uteis;

class MovementCameraDto
{
    private $fields = ["id", "uuid", "nsr", "data", "hours", "processed", "sensor_code", "concierge", "plate", "created_at", "deleted_at","remote_ref","update_at","fk_branch_id"];
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
    public  $update_at;
    public  $remote_ref;
    public  $fk_branch_id;
    

    public function __construct($data = null)
    {
        if (!is_null($data) && is_array($data)) {
            foreach ($data as $key => $row) {
                if (in_array($key, $this->fields)) {
                    $this->$key = $row;
                }
            }
        }

        $this->toUuid();
    }

    function toArray()
    {
        $rows = [];
        foreach ($this->fields as $key => $value) {
            $rows[$value] = $this->$value;
        }
        return $rows;
    }

    function toUuid()
    {
        
        $this->uuid = sprintf("%s_%s%s%s", $this->plate, $this->sensor_code, $this->concierge,Uteis::extractNumbers($this->created_at));
        return $this->uuid;

    }
}
