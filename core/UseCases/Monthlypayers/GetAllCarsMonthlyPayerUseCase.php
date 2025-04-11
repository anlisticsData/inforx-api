<?php
namespace UseCases\Monthlypayers;

use Exception;
use Interfaces\IUserCase;
use Interfaces\MonthlyPayers\IMonthlyPayersRepository;

class GetAllCarsMonthlyPayerUseCase implements IUserCase
{
    private IMonthlyPayersRepository $iMonthyPlateRepository;
    
    public function __construct(IMonthlyPayersRepository $iMonthyPlateRepository)
    {   
        $this->iMonthyPlateRepository = $iMonthyPlateRepository;
        return $this;
    }
    public  function execute($monthlyCode){
        try {
            return $this->iMonthyPlateRepository->findAllCarsBy($monthlyCode);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}