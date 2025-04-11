<?php

namespace Models;

class ParkingTransaction {
    private $inputs = [
        "id", "created_at", "id_type", "plate", "name_type", "hours", 
        "minutes", "receipt_by_box", "payment_change", "discount_applied", 
        "park_entry_date", "park_date_departure", "fk_agreement_id",
        "fk_branch_id"
    ];

    public $id;
    public $created_at;
    public $id_type;
    public $plate;
    public $name_type;
    public $hours;
    public $minutes;
    public $receipt_by_box;
    public $payment_change;
    public $discount_applied;
    public $park_entry_date;
    public $park_date_departure;
    public $fk_agreement_id;
    public $fk_branch_id;

    public function __construct($data = null) {
        if (!is_null($data) && is_array($data)) {
            foreach ($data as $key => $value) {
                if (in_array($key, $this->inputs)) {
                    $this->$key = $value;
                }
            }
        }
    }

    public function toArray() {
        $rows = [];
        foreach ($this->inputs as $key => $value) {
            $rows[$value] = $this->$value;
        }
        return $rows;
    }
}

?>
