<?php
namespace UseCases\Cartypes;

use Interfaces\Car\IMonthlyRepository;

 

class GetByPlateUseCase{
    private  IMonthlyRepository $iMonthlyRepository;
    public function __construct(IMonthlyRepository $iMonthlyRepository){
        $this->iMonthlyRepository = $iMonthlyRepository;
    }     
    public function execute($plate,$brand){
        return $this->iMonthlyRepository->byPlate($plate,$brand);
    }

}


