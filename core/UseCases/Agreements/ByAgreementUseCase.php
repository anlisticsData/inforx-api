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

class ByAgreementUseCase implements IUserCase
{
 

    private IAgreementRepository $iAgreementRepository;
    private IBranchesRepository $iBranchesRepository;
    private $errors=[];
    public function __construct( IAgreementRepository $iAgreementRepository, IBranchesRepository $iBranchesRepository )
    {
        $this->iAgreementRepository=$iAgreementRepository;
        $this->iBranchesRepository=$iBranchesRepository;
    }
    public function execute($code,$branch)
    {
        try {

            $errors=[];
            if(count($this->iBranchesRepository->SearchBy(trim($branch)))==0){
                $errors[]=Strings::$STR_BRANCH__INVALID;
            }
            if(Uteis::isNullOrEmpty($code)){
                $errors[]=sprintf(Strings::$STR_INPUTS_INVALID,Strings::$APP_AGREEMENT_INPUT_BASE_CODE);
            }
            if(count($errors) > 0){
                throw new Exception(implode("\n",$errors),HttpStatus::$HTTP_CODE_BAD_REQUEST);
            }
            return $this->iAgreementRepository->byAgreement($code,$branch);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}
