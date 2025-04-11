<?php

namespace  UseCases\Cartypes;

use Commons\Uteis;
use Interfaces\Car\ICartype;

class TypeOfVehiclesUseCase
{
    private  ICartype $iCartypeRepository;
    public function __construct(ICartype $iCartypeRepository)
    {
        $this->iCartypeRepository =  $iCartypeRepository;
    }
    public function execute()
    {
        return $this->iCartypeRepository->typeOfVehicles();
    }
}
