<?php

namespace UseCases\Agreements;

use Exception;
use Commons\Uteis;
use Dtos\AgreementDto;
use Dtos\AllDto;
use Resources\Strings;
use Requests\AllRequest;
use Interfaces\IUserCase;
use Resources\HttpStatus;
use Interfaces\Branches\IBranchesRepository;
use Interfaces\Agreements\IAgreementRepository;

class AllAgreementUseCase implements IUserCase
{
 

    private IAgreementRepository $iAgreementRepository;
    private IBranchesRepository $iBranchesRepository;
    private $errors=[];
    public function __construct( IAgreementRepository $iAgreementRepository, IBranchesRepository $iBranchesRepository )
    {
        $this->iAgreementRepository=$iAgreementRepository;
        $this->iBranchesRepository=$iBranchesRepository;
    }
    public function execute($branch,AllDto $allDto,$all=false)
    {
        try {
            $errors=[];
            if(count($this->iBranchesRepository->SearchBy(trim($branch)))==0){
                $errors[]=Strings::$STR_BRANCH__INVALID;
            }
            if(count($errors) > 0){
                throw new Exception(implode("\n",$errors),HttpStatus::$HTTP_CODE_BAD_REQUEST);
            }
            if($all){
                 $agreements = $this->iAgreementRepository->allAgreements($branch);
            }else{
                 $agreements = $this->iAgreementRepository->allAgreementsPaginator($branch,$allDto);
            }
            return $agreements;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }

        return null;
    }
}
