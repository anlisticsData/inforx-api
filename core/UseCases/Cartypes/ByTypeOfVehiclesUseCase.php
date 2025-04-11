<?php

namespace  UseCases\Cartypes;

use Commons\Uteis;
use Interfaces\Car\ICartype;

class ByTypeOfVehiclesUseCase
{
    private  ICartype $iCartypeRepository;
    public function __construct(ICartype $iCartypeRepository)
    {
        $this->iCartypeRepository =  $iCartypeRepository;
    }
    public function execute($code)
    {
        return $this->iCartypeRepository->byTypeOfVehicles($code);
    }
}
