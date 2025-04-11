<?php

namespace Dtos;


class SummaryBoxDay
{
    private $inputs = ["summary","cancelled","agreements","agreementsSummary"];
    public $cancelled;
    public $summary;
    public $agreements;
    public $agreementsSummary=[];
    public $paymentsSummary=null;


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
