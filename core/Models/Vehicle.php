<?php
namespace Models;

class Vehicle
{
    private $inputs = ["id", "plate", "model", "brand", "description", "created_at", "cancel_at"];
    
    public $id;
    public $plate;
    public $model;
    public $brand;
    public $description;
    public $created_at;
    public $cancel_at;

    // Construtor que preenche as propriedades com base no array de dados
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

    // Método que converte a classe para um array associativo
    public function toArray()
    {
        $rows = [];
        foreach ($this->inputs as $key => $value) {
            $rows[$value] = $this->$value;
        }
        return $rows;
    }
}

?>
