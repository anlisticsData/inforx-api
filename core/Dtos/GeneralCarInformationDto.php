<?php

namespace Dtos;

class GeneralCarInformationDto
{
    private $inputs = [
        "movement",
        "price_grid",
        "complete_information",
        "used_grid",
        "total",
        "monthly_day_expiry_date",
        "outstanding_debts",
        "is_monthly",
        "balance",
        "validade",
        "intervals_used"
    ];

    public $movement;
    public $price_grid;
    public $complete_information;
    public $used_grid;
    public $total;
    public $monthly_day_expiry_date;
    public $outstanding_debts;
    public $is_monthly;
    public $balance;
    public $validade;
    public $intervals_used;

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
}
