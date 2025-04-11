<?php
namespace UseCases\Monthlypayers;

use Exception;
use Commons\Uteis;
use Models\MonthlyPayer;
use Dtos\MonthlyPayerDto;
use Interfaces\IUserCase;
use Interfaces\MonthlyPayers\IMonthlyPayersRepository;
use Resources\HttpStatus;
use Resources\Strings;

class ReserveAPlaceForTheMonthlyMemberUseCase implements IUserCase
{
    private IMonthlyPayersRepository $iMonthyPlateRepository;
    public function __construct(IMonthlyPayersRepository $iMonthyPlateRepository)
    {
         $this->iMonthyPlateRepository = $iMonthyPlateRepository;
        return $this;
    }
    public function execute($fCustomers,$fkBranch)
    {
        try {
            $AllMonthlyPayers=$this->iMonthyPlateRepository->findAllCustomerAndBranche($fCustomers,$fkBranch);
            $vacaciones =0;
            foreach($AllMonthlyPayers as $row){
                $vacaciones+=$row->used_vacancies;
            }
            return $vacaciones;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}
