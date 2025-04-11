<?php

use Requests\IdRequest;
use Commons\HttpRequests;
use Commons\ResponseJson;
use Resources\HttpStatus;
use Middleware\Authorization;
use UseCases\Agreements\ByAgreementUseCase;
use Repositories\Branches\BranchesRepository;
use Repositories\Agreements\AgreementRepository;

require_once __DIR__ . "./../../core/Settings.php";
try {
    Authorization::Init();
    $playload = Authorization::Token();
    $request = new IdRequest(HttpRequests::Requests());
    $branch=$playload['branch'];
    $byAgreementUseCase =  new ByAgreementUseCase(new AgreementRepository(),new BranchesRepository());
    $result=$byAgreementUseCase->execute($request->id,$branch);
    new ResponseJson(HttpStatus::$HTTP_CODE_OK, $result);
} catch (Exception $e) {
    new ResponseJson($e->getCode(), $e->getMessage());
}
