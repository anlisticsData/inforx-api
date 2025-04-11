<?php

namespace Requests;

use Commons\DateTimeCalculator;
use PDO;
use Exception;
use Commons\Uteis;
use Resources\Strings;
use Resources\HttpStatus;
use Interfaces\IRequestValidate;
 
class PeriodRequest implements IRequestValidate{
    private $inputs=["initial","end"];
    public  $initial;
    public  $end;
    
    public function __construct($requesInputs){
        foreach($this->inputs as $key => $input){
            if(isset($requesInputs[$input])){
                $this->$input =$requesInputs[$input];
            }
        }
        $date =  date("Y-m-d");
        if(Uteis::isNullOrEmpty($this->initial) && Uteis::isNullOrEmpty($this->end) ){
            $this->initial = $date;
            $this->end = $date;
            $this->end  = date("Y-m-d", strtotime($this->end . ' +1 day'));
        }
        if(Uteis::isNullOrEmpty($this->initial)){
            $this->initial = $date;
        }
        if(Uteis::isNullOrEmpty($this->end)){
            $this->end = $date;
        }

        
        if($this->initial===$this->end  ){
            $this->end  = date("Y-m-d", strtotime($this->end . ' +1 day'));
        }


       

        if(!DateTimeCalculator::isDateLessThan($this->initial,$this->end)){
            $aux=$this->initial;
            $this->initial =  $this->end;
            $this->end =  $aux;
        }
    }

    function isValid(){}
}