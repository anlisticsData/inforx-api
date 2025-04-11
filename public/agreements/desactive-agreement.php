<?php

use Resources\Strings;
use Requests\IdRequest;
use Commons\HttpRequests;
use Commons\ResponseJson;
use Resources\HttpStatus;
use Middleware\Authorization;
use UseCases\Agreements\ByAgreementUseCase;
use Repositories\Branches\BranchesRepository;
use Repositories\Agreements\AgreementRepository;
use UseCases\Agreements\DesactiveAgreementUseCase;

 

require_once __DIR__ . "./../../core/Settings.php";
try {
    Authorization::Init();
    $playload = Authorization::Token();
    $request = new IdRequest(HttpRequests::Requests());
    $branch=$playload['branch'];
    $byAgreementUseCase =  new ByAgreementUseCase(new AgreementRepository(),new BranchesRepository());
    if(is_null($byAgreementUseCase->execute($request->id,$branch))){
        throw new Exception(Strings::$STR_CODE_RECOVER_INVALID_NOT_EXIST,HttpStatus::$HTTP_CODE_BAD_REQUEST);
    }
    $desactiveAgreementUseCase =  new DesactiveAgreementUseCase(new AgreementRepository(),new BranchesRepository());
    $result=$desactiveAgreementUseCase->execute($branch,$request->id);
    new ResponseJson(HttpStatus::$HTTP_CODE_OK, $result);
} catch (Exception $e) {
    new ResponseJson($e->getCode(), $e->getMessage());
}
