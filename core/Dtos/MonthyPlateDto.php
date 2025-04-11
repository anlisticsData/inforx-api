<?php
namespace Dtos;



use Interfaces\IToArray;

class MonthyPlateDto implements IToArray
{
    private $inputs = ["id","fk_curtomers","fk_branch","monthly_filiais_clientes_id","types_of_cars_id","plate","color","created_at","deleted_at"];
    public $id;
    public $fk_curtomers;
    public $fk_branch;
    public $monthly_filiais_clientes_id;
    public $types_of_cars_id;
    public $plate;
    public $color;
    public $created_at;
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

    function toArray()  {
        $rows=[];
        foreach($this->inputs as $key =>$value){
            $rows[$value]=$this->$value;
        }
        return $rows;
    }
}
