<?php


namespace UseCases;

use Dtos\CarTypeDto;
use Interfaces\Car\ICartype;


class GetTypeAllUserCase{
    private  $cardRepositorie;
    public function __construct($cardRepositorie){
        $this->cardRepositorie = $cardRepositorie;
    }     
    public function execute(){
        return $this->cardRepositorie->all();
    }

}


