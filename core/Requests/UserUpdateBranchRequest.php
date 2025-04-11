<?php

namespace Requests;

use PDO;
use Exception;
use Commons\Uteis;
use Resources\Strings;
use Resources\HttpStatus;
use Interfaces\IRequestValidate;
 
class UserUpdateBranchRequest implements IRequestValidate{
    private $inputs=["description","address","cnpj","phone","text_ticket",
                     "available_vacancies","free_time","insurance_expiration","city","state","avatar_id","id"];
    public  $description;
    public  $address;
    public  $cnpj;
    public  $phone;
    public  $text_ticket;
      public  $free_time;
    public  $seguro;
    public  $city;
    public  $state;
    public  $avatar_id;
    public  $id;
    public $insurance_expiration;
    public $available_vacancies; 
    
    public function __construct($requesInputs){
        foreach($this->inputs as $key => $input){
            if(isset($requesInputs[$input])){
                $this->$input =$requesInputs[$input];
            }
        }
        $this->isValid();
    }

    function isValid()
    {
        
    }
}