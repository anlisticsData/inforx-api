<?php 

namespace Models;

use Interfaces\IToArray;

class Movements implements IToArray
{
    private $inputs = ["park_id", "park_vehicle_plate", "park_entry_date", "park_date_departure", "prisma",
            "obs_entry", "obs_exit", "double_vacancy", "car_monthly_id", "user_entry", "user_exit", "branches_id",
            "uuid_ref","uuid_id_plate_direction_create","created_at","permanence","hours","fk_color_id","fk_cartype_id","fk_type_of_vehicle",
            "calculate","interval","type_print","deleted_at"
        
        ];
    
    public $park_id;
    public $park_vehicle_plate;
    public $park_entry_date;
    public $park_date_departure;
    public $prisma;
    public $obs_entry;
    public $obs_exit;
    public $double_vacancy;
    public $car_monthly_id;
    public $user_entry;
    public $user_exit;
    public $branches_id;
    public $uuid_ref;
    public $uuid_id_plate_direction_create;
    public $permanence;
    public $hours;
    public $created_at;
    public $fk_color_id;
    public $fk_cartype_id;
    public $fk_type_of_vehicle;
    public $calculate;
    public $interval;
    public $type_print;
    public $deleted_at;


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
