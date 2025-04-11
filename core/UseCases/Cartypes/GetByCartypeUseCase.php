<?php
namespace UseCases\Cartypes;

use Commons\Uteis;
use Interfaces\Car\ICartype;

class GetByCartypeUseCase{
    private  ICartype $iCartypeRepositori;
    public function __construct(ICartype $iCartypeRepositori){
        $this->iCartypeRepositori = $iCartypeRepositori;
    }     
    public function execute($code){
        return $this->iCartypeRepositori->one($code);
    }

}


