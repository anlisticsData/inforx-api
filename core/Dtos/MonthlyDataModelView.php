<?php

namespace Dtos;

use Interfaces\IToArray;

class MonthlyDataModelView implements IToArray
{
    private $inputs = [
        'id',
        'monthly_filiais_clientes_id',
        'types_of_cars_id',
        'plate',
        'color',
        'created_at',
        'model',
        'brand',
        'uuid',
        'fk_monthy_players_id',
        'fk_car',
        'cancel_at',
        'monthly_id',
        'monthly_date_registration',
        'monthly_name',
        'monthly_date_birth',
        'monthly_cpf',
        'monthly_rg',
        'monthly_email',
        'monthly_fixed_phone',
        'monthly_telefone_mobile',
        'monthly_cep',
        'monthly_address',
        'monthly_complement',
        'monthly_neighborhood',
        'monthly_city',
        'monthly_status',
        'monthly_day_expiry_date',
        'monthly_observation',
        'monthly_date_change',
        'quantity_of_vacancies',
        'active_monthly',
        'fk_user_id',
        'monthly_fee_amount',
        'type_of_monthly_fee'
    ];
    public $id;
    public $monthly_filiais_clientes_id;
    public $types_of_cars_id;
    public $plate;
    public $color;
    public $created_at;
    public $model;
    public $brand;
    public $uuid;
    public $fk_monthy_players_id;
    public $fk_car;
    public $cancel_at;
    public $monthly_id;
    public $monthly_date_registration;
    public $monthly_name;
    public $monthly_date_birth;
    public $monthly_cpf;
    public $monthly_rg;
    public $monthly_email;
    public $monthly_fixed_phone;
    public $monthly_telefone_mobile;
    public $monthly_cep;
    public $monthly_address;
    public $monthly_complement;
    public $monthly_neighborhood;
    public $monthly_city;
    public $monthly_status;
    public $monthly_day_expiry_date;
    public $monthly_observation;
    public $monthly_date_change;
    public $quantity_of_vacancies;
    public $active_monthly;
    public $fk_user_id;
    public $monthly_fee_amount;
    public $type_of_monthly_fee;



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
