<?php

namespace Models;


class Price
{
    private $inputs = [
        "pricing_id",
        "pricing_category",
        "pricing_value_first_half_hour",
        "pricing_value_first_hour",
        "pricing_of_other_hours",
        "pricing_monthly_value",
        "pricing_value_half_day",
        "pricing_daily_value",
        "pricing_value_24_hours",
        "pricing_value_overnight",
        "active_pricing",
        "pricing_date_change",
        "pricing_value_first_half_hour_tolerance",
        "pricing_value_first_hour_tolerance",
        "pricing_of_other_hours_tolerance",
        "pricing_monthly_value_tolerance",
        "pricing_value_half_day_tolerance",
        "pricing_daily_value_tolerance",
        "pricing_value_24_hours_tolerance",
        "pricing_value_overnight_tolerance",
        "branches_id"
    ];

    public $pricing_id;
    public $pricing_category;
    public $pricing_value_first_half_hour;
    public $pricing_value_first_hour;
    public $pricing_of_other_hours;
    public $pricing_monthly_value;
    public $pricing_value_half_day;
    public $pricing_daily_value;
    public $pricing_value_24_hours;
    public $pricing_value_overnight;
    public $active_pricing;
    public $pricing_date_change;
    public $pricing_value_first_half_hour_tolerance;
    public $pricing_value_first_hour_tolerance;
    public $pricing_of_other_hours_tolerance;
    public $pricing_monthly_value_tolerance;
    public $pricing_value_half_day_tolerance;
    public $pricing_daily_value_tolerance;
    public $pricing_value_24_hours_tolerance;
    public $pricing_value_overnight_tolerance;
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
    }

    function toArray()
    {
        $rows = [];
        foreach ($this->inputs as $value) {
            $rows[$value] = $this->$value;
        }
        return $rows;
    }
}
