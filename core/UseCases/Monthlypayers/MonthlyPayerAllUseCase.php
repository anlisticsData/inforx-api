<?php
namespace UseCases\Monthlypayers;

use Exception;
use Commons\Uteis;
use Resources\Strings;
use Models\MonthlyPayer;
use Commons\ResponseJson;
use Dtos\MonthlyPayerDto;
use Interfaces\Branches\IBranchesRepository;
use Interfaces\IUserCase;
use Resources\HttpStatus;
use Interfaces\MonthlyPayers\IMonthlyPayersRepository;

class MonthlyPayerAllUseCase implements IUserCase
{
    private IMonthlyPayersRepository $iMonthyPlateRepository;
 
    public function __construct(IMonthlyPayersRepository $iMonthyPlateRepository)
    {   
        $this->iMonthyPlateRepository = $iMonthyPlateRepository;
        return $this;
    }
    public function execute(MonthlyPayerDto $monthlyDto)
    {
        try {
            return $this->iMonthyPlateRepository->findAll($monthlyDto->fk_curtomers,$monthlyDto->fk_branch);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}
