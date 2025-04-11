<?php

namespace Requests;

 
use Exception;
use Commons\Uteis;
use Resources\Strings;
use Resources\HttpStatus;
use Commons\ValidateInputs;
use Interfaces\IRequestValidate;

 



class BanchRequest implements IRequestValidate{
    private $inputs = ["id","description","status","cnpj","address","phone","text_ticket","email","city","state","free_time",
                       "costumers_id","available_vacancies","insurance_expiration","deleted_at","created_at","avatar_id",
                        "search","pager"];

    public $id;
    public $description;
    public $status;
    public $cnpj;
    public $address;
    public $created_at;
    public $phone;
    public $text_ticket;
    public $email;
    public $city;
    public $state;
    public $free_time;
    public $costumers_id;
    public $available_vacancies;
    public $insurance_expiration;
    public $deleted_at;
    public $avatar_id;
    public $search;
    public $pager;








    public function __construct($requesInputs=null){
        
        foreach($this->inputs as $key => $input){
            if(isset($requesInputs[$input])){
                $this->$input =$requesInputs[$input];
            }
        }
        $this->isValid();
    }

    function isValid()
    {
        $errors=[];
        if(Uteis::isNullOrEmpty($this->description)){
           $errors[]=str_replace("[:input]","description",Strings::$STR_INPUTS_MANDATORY);
        }
        if( Uteis::isNullOrEmpty($this->cnpj) ){
            $errors[]=str_replace("[:input]","cnpj",Strings::$STR_INPUTS_MANDATORY);
        }
 
        
    }
}