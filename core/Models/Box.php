<?php

namespace Models;


class Box
{
    private $inputs = [
        "id",
        "type",
        "date",
        "amount",
        "created_at",
        "users_id",
        "branches_id",
        "openined_date",
        "closure_date",
        "openined_amount",
        "closure_amount",
        "users_id"
    ];
    public $id;
    public $type;
    public $date;
    public $amount;
    public $created_at;
    public $users_id;
    public $branches_id;
    public $openined_date;
    public $closure_date;
    public $openined_amount;
    public $closure_amount;


    public function __construct($data = null)
    {
        if (!is_null($data) && is_array($data)) {
            foreach ($data as $key => $row) {
                if (in_array($key, $this->inputs)) {
                    $this->$key = $row;
                }
            }
        }
    }
    function toArray()
    {
        $rows = [];
        foreach ($this->inputs as $key => $value) {
            $rows[$value] = $this->$value;
        }
        return $rows;
    }
}
