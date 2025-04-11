<?php

use Dtos\AgreementDto;
use Commons\HttpRequests;
use Commons\ResponseJson;
use Commons\Uteis;
use Resources\HttpStatus;
use Middleware\Authorization;
use Requests\AgreementRequest;
use Repositories\Branches\BranchesRepository;
use UseCases\Agreements\CreateAgreementUseCase;
use Repositories\Agreements\AgreementRepository;

 
require_once __DIR__ . "./../../core/Settings.php";
try {
    Authorization::Init();
    $playload = Authorization::Token();
    $request = new AgreementRequest(HttpRequests::Requests());
    $request->fk_branche_id=$playload['branch'];


    $createAgreementUseCase =  new CreateAgreementUseCase(new AgreementRepository(),new BranchesRepository());
    $result=$createAgreementUseCase->execute(new AgreementDto($request->toArray()));
    new ResponseJson(HttpStatus::$HTTP_CODE_CREATED, $result);
} catch (Exception $e) {
    new ResponseJson($e->getCode(), $e->getMessage());
}
