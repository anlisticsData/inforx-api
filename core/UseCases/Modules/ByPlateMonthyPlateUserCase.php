<?php

namespace UseCases\Modules;

use Commons\Uteis;
use Dtos\ModuleDto;
use Models\MonthyPlate;
use Interfaces\IUserCase;
use Interfaces\MonthyPlate\IMonthyPlateRepository;

class ByPlateMonthyPlateUserCase implements IUserCase{
    private IMonthyPlateRepository $iMonthyPlateRepository;
    public function __construct(IMonthyPlateRepository $iMonthyPlateRepository){
        $this->iMonthyPlateRepository = $iMonthyPlateRepository;
    }     
    public function execute($plate){
        
       $monthyPlate = new MonthyPlate($this->iMonthyPlateRepository->findOnePlate($plate));
       if(Uteis::isNullOrEmpty($monthyPlate->plate)) return null;
       return $monthyPlate;
    }
}
