<?php
namespace Dtos;

use Commons\Uteis;

class PaymentSummaryDto {
    private $inputs = [
        "totalRecebido",
        "totalDescontos",
        "vehicles",
        "separate",
        "accredited",
        "monthlypayers",
        "payment_methods",
        "cancelations",
        "not_exit"
    ];

    
    public function __construct($data = null) {

        
        if (!is_null($data) && is_array($data)) {
            foreach ($data as $key => $value) {
                if (in_array($key, $this->inputs)) {
                    $this->$value = $value;
                }
            }
        }
    }

    public function toArray() {
        $rows = [];
        foreach ($this->inputs as $key) {
            $rows[$key] = $this->data[$key] ?? null;
        }
        return $rows;
    }

    // Getters e Setters para maior segurança
    public function __get($name) {
        return $this->data[$name] ?? null;
    }

    public function __set($name, $value) {
        if (in_array($name, $this->inputs)) {
            if (is_numeric($value)) {
                $this->data[$name] = number_format($value, 2, '.', '');
            } else {
                $this->data[$name] = $value;
            }
        }
    }
}
