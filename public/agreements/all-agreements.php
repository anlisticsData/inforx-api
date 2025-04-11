<?php

use Requests\IdRequest;
use Requests\AllRequest;
use Commons\HttpRequests;
use Commons\ResponseJson;
use Commons\Uteis;
use Dtos\AllDto;
use Resources\HttpStatus;
use Middleware\Authorization;
use UseCases\Agreements\AllAgreementUseCase;
use Repositories\Branches\BranchesRepository;
use Repositories\Agreements\AgreementRepository;

 
 
require_once __DIR__ . "./../../core/Settings.php";
try {
    Authorization::Init();
    $playload = Authorization::Token();
    
    

    $request = new AllRequest(HttpRequests::Requests());

    $branch=Authorization::getBranchCode();
    $allAgreementUseCase =  new AllAgreementUseCase(new AgreementRepository(),new BranchesRepository());
    $result=$allAgreementUseCase->execute($branch,new AllDto($request->toArray()));
    new ResponseJson(HttpStatus::$HTTP_CODE_OK, $result);
} catch (Exception $e) {
    new ResponseJson($e->getCode(), $e->getMessage());
}
