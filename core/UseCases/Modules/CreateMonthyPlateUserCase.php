<?php

namespace UseCases\Modules;

use Exception;
use MockPlate;
use Commons\Uteis;
use Dtos\ModuleDto;
use Models\MonthyPlate;
use Dtos\MonthyPlateDto;
use Interfaces\IUserCase;
use Interfaces\MonthyPlate\IMonthyPlateRepository;

class CreateMonthyPlateUserCase implements IUserCase{
    private IMonthyPlateRepository $iMonthyPlateRepository;
    public function __construct(IMonthyPlateRepository $iMonthyPlateRepository){
        $this->iMonthyPlateRepository = $iMonthyPlateRepository;
    }     
    public function execute(MonthyPlateDto $monthyPlate){
        try{
            return $this->iMonthyPlateRepository->registerNewPlateAtTheBranch(new MonthyPlate($monthyPlate->toArray()));
        }catch(Exception $e){
            throw new Exception($e->getMessage(),$e->getCode());
        }
    }
}
