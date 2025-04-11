<?php

namespace Commons;


class TypeDateTime{

    private $inputs=["year","day","month","hour","minute","senconds"];
    public  $year;
    public  $day;
    public  $month;
    public  $hour;
    public  $minute;
    public  $senconds;
    public function __construct($paramets=null){
        foreach($this->inputs as $key => $input){
            if(isset($paramets[$input])){
                $this->$input =$paramets[$input];
            }
        }

    }


}