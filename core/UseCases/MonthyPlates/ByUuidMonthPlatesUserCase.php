<?php

namespace UseCases\MonthyPlates;

use Exception;
use Interfaces\IUserCase;
use Interfaces\MonthyPlate\IMonthyPlateRepository;

class ByUuidMonthPlatesUserCase implements IUserCase
{
    private IMonthyPlateRepository $iMonthyPlateRepository;
    public function __construct(IMonthyPlateRepository $iMonthyPlateRepository)
    {
        $this->iMonthyPlateRepository = $iMonthyPlateRepository;
        return $this;
    }
    public function execute($plate)
    {
        try {
            return $this->iMonthyPlateRepository->completeVehicleInformation($plate);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}
