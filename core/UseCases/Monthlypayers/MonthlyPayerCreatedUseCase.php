<?php
namespace UseCases\Monthlypayers;

use Exception;
use Commons\Uteis;
use Resources\Strings;
use Models\MonthlyPayer;
use Commons\ResponseJson;
use Dtos\MonthlyPayerDto;
use Interfaces\IUserCase;
use Resources\HttpStatus;
use Commons\DateTimeCalculator;
use Interfaces\Branches\IBranchesRepository;
use Interfaces\MonthlyPayers\IMonthlyPayersRepository;

class MonthlyPayerCreatedUseCase implements IUserCase
{
    private IMonthlyPayersRepository $iMonthyPlateRepository;
    private ReserveAPlaceForTheMonthlyMemberUseCase $reserveAPlaceForTheMonthlyMemberUseCase;
    private IBranchesRepository $iBranchesRepository;
    public function __construct(IBranchesRepository $iBranchesRepository,IMonthlyPayersRepository $iMonthyPlateRepository,
        ReserveAPlaceForTheMonthlyMemberUseCase $reserveAPlaceForTheMonthlyMemberUseCase)
    {   
         $this->iMonthyPlateRepository = $iMonthyPlateRepository;
         $this->reserveAPlaceForTheMonthlyMemberUseCase=$reserveAPlaceForTheMonthlyMemberUseCase;
         $this->iBranchesRepository = $iBranchesRepository;
        return $this;
    }
    public function execute(MonthlyPayerDto $monthlyDto)
    {
        try {
            
           
            $monthlyDto->dt_nasc="0000-00-00";
            $monthlyDto->dt_nasc=null;
            $vacaciones =  $this->reserveAPlaceForTheMonthlyMemberUseCase->execute($monthlyDto->fk_curtomers,$monthlyDto->fk_branch);
            $availableVacancies = $this->iBranchesRepository->SearchByCustomer($monthlyDto->fk_branch,$monthlyDto->fk_curtomers);
            if(count($availableVacancies)==0) throw new Exception(Strings::$STR_BRANCH__INVALID,HttpStatus::$HTTP_CODE_BAD_REQUEST);
            $freeVacancies=$availableVacancies[0]['available_vacancies'] - $vacaciones;
            if($freeVacancies < 1){
                throw new Exception(Strings::$STR_VACACIONES_INVALID,HttpStatus::$HTTP_CODE_BAD_REQUEST);
            } 
            $resultData= $this->iMonthyPlateRepository->findOneEmailOrCpfOrRg($monthlyDto);
            if(count($resultData) > 0) throw new Exception(Strings::$STR_MONTHLYPAYER_INVALID,HttpStatus::$HTTP_CODE_BAD_REQUEST);
            return $this->iMonthyPlateRepository->created(new MonthlyPayer($monthlyDto->toArray()));
        } catch (Exception $e) {
            
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}
