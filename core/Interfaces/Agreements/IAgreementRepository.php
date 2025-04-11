<?php

namespace Interfaces\Agreements;

use Dtos\AllDto;
use Models\Agreement;

interface IAgreementRepository{
    function create(Agreement $agreement);
    function byAgreement($codeAgreement,$branchCode);
    function activeAgreement($codeAgreement,$branchCode);
    function desactiveAgreement($codeAgreement,$branchCode);
    function delete($codeAgreement,$branchCode);
    function allAgreements($codeBranch);
    function allAgreementsPaginator($codeBranch,AllDto $allDto);
    function update(Agreement $agreement);
    function nameOfTheAgreementExists($name,$branchCode);


    
}