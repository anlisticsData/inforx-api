<?php

namespace Dtos;

use Commons\Uteis;

class IntervalDto
{
    private $inputs = ["initialDate", "endDate"];
    public $initialDate;
    public $endDate;

    public function __construct($data = null)
    {
        if (!is_null($data) && is_array($data)) {
            foreach ($data as $key => $row) {
                if (in_array($key, $this->inputs)) {
                    $this->$key = $row;
                }
            }
        }

        if(Uteis::isNullOrEmpty($this->initialDate)){
            $this->initialDate = date("Y-m-d H:m:s");
        }

        if(Uteis::isNullOrEmpty($this->endDate)){
            $this->endDate = date("Y-m-d H:m:s");
        }
    }



    public function separateDate(){
        return [
            "year"=>date("Y"),"month"=>date("m"),"day"=>date("d"),
            "hour"=>date("H"),"minute"=>date("m"),"seconds"=>date("s")
        ];
    }


}
