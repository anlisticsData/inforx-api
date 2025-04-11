<?php

use Dtos\AgreementDto;
use Commons\HttpRequests;
use Commons\ResponseJson;
use Resources\HttpStatus;
use Middleware\Authorization;
use Requests\AgreementUpdateRequest;
use Repositories\Branches\BranchesRepository;
use UseCases\Agreements\UpdateAgreementUseCase;
use Repositories\Agreements\AgreementRepository;

 

require_once __DIR__ . "./../../core/Settings.php";
try {
    Authorization::Init();
    $playload = Authorization::Token();
    $request = new AgreementUpdateRequest(HttpRequests::Requests());
    $request->fk_branche_id=$playload['branch'];
    $updateAgreementUseCase =  new UpdateAgreementUseCase(new AgreementRepository(),new BranchesRepository());
    $result=$updateAgreementUseCase->execute(new AgreementDto($request->toArray()));
    if($result)  new ResponseJson(HttpStatus::$HTTP_CODE_NO_CONTENT, $result);
    new ResponseJson(HttpStatus::$HTTP_CODE_BAD_REQUEST);
} catch (Exception $e) {
    new ResponseJson($e->getCode(), $e->getMessage());
}
