<?php

namespace UseCases\Agreements;

use Exception;
use Commons\Uteis;
use Models\Agreement;
use Dtos\AgreementDto;
use Interfaces\IUserCase;
use Interfaces\Agreements\IAgreementRepository;
use Interfaces\Branches\IBranchesRepository;
use Resources\HttpStatus;
use Resources\Strings;

class UpdateAgreementUseCase implements IUserCase
{
 

    private IAgreementRepository $iAgreementRepository;
    private IBranchesRepository $iBranchesRepository;
    private $errors=[];
    public function __construct( IAgreementRepository $iAgreementRepository, IBranchesRepository $iBranchesRepository )
    {
        $this->iAgreementRepository=$iAgreementRepository;
        $this->iBranchesRepository=$iBranchesRepository;
    }
    public function execute(AgreementDto $agreementDto)
    {
        try {



          



            $errors=[];
            if(count($this->iBranchesRepository->SearchBy(trim($agreementDto->fk_branche_id)))==0){
                $errors[]=Strings::$STR_BRANCH__INVALID;
             
            }
            

           
           
            if(Uteis::isNullOrEmpty($agreementDto->id)){
                $errors[]=sprintf(Strings::$STR_INPUTS_INVALID,Strings::$APP_AGREEMENT_INPUT_BASE_CODE);
            }
            if(Uteis::isNullOrEmpty($agreementDto->name)){
                $errors[]=sprintf(Strings::$STR_INPUTS_INVALID,Strings::$APP_AGREEMENT_INPUT_NAME);
            }

            if(Uteis::isNullOrEmpty($agreementDto->start)){
                $errors[]=sprintf(Strings::$STR_INPUTS_INVALID,Strings::$APP_AGREEMENT_INPUT_START_INTERVAL);
            }

            if(Uteis::isNullOrEmpty($agreementDto->end)){
                $errors[]=sprintf(Strings::$STR_INPUTS_INVALID,Strings::$APP_AGREEMENT_INPUT_END_INTERVAL);
            }

            if(!Uteis::validateSchedule($agreementDto->start) || !Uteis::validateSchedule($agreementDto->end)){
                $errors[]=sprintf(Strings::$STR_INPUTS_INVALID,Strings::$APP_AGREEMENT_INPUT_START_OR_END_INTERVAL);
            }


            if(count($errors) > 0){
                throw new Exception(implode("\n",$errors),HttpStatus::$HTTP_CODE_BAD_REQUEST);
            }
            return $this->iAgreementRepository->update(new Agreement($agreementDto->toArray()));
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}
