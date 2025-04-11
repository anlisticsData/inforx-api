<?php

namespace Dtos;
 
class BranchesDto {
    private $inputs = ["id","description","status","cnpj","address","phone","text_ticket","email","city","state","free_time",
                       "costumers_id","available_vacancies","insurance_expiration","deleted_at","avatar_id","avatar","search","settings","created_at"];

    public $id;
    public $description;
    public $status;
    public $cnpj;
    public $address;
    
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
    public $avatar;
    public $settings;
    public $created_at;



    public function __construct($data=null){
        if(!is_null($data) && is_array($data)){
            foreach($data as $key =>$row ){
                if(in_array($key,$this->inputs)){
                   $this->$key=$row;     
                }
            }
        }
    }
}