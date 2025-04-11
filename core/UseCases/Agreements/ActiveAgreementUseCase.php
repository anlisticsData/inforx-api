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

class ActiveAgreementUseCase implements IUserCase
{
 

    private IAgreementRepository $iAgreementRepository;
    private IBranchesRepository $iBranchesRepository;
    private $errors=[];
    public function __construct( IAgreementRepository $iAgreementRepository, IBranchesRepository $iBranchesRepository )
    {
        $this->iAgreementRepository=$iAgreementRepository;
        $this->iBranchesRepository=$iBranchesRepository;
    }
    public function execute($branchCode,$agreementCode)
    {
         try {
            $errors=[];
            if(count($this->iBranchesRepository->SearchBy(trim($branchCode)))==0){
                $errors[]=Strings::$STR_BRANCH__INVALID;
            }
            if(Uteis::isNullOrEmpty($agreementCode)){
                $errors[]=Strings::$APP_AGREEMENT_INPUT_BASE_CODE;
            }

            if(count($errors) > 0){
                throw new Exception(implode("\n",$errors),HttpStatus::$HTTP_CODE_BAD_REQUEST);
            }
            return $this->iAgreementRepository->activeAgreement($agreementCode,$branchCode);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}
