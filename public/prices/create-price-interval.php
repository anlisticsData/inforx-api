<?php
use Commons\HttpRequests;
use Commons\ResponseJson;
use Resources\HttpStatus;
use Dtos\PricesIntervalsDto;
use Middleware\Authorization;
use Requests\PricesByIntervalsRequest;
use Repositories\Price\PriceRepository;
use UseCases\Price\CreatePriceIntervalUserCase;
require_once __DIR__ . "./../../core/Settings.php";
try {
    Authorization::Init();
    $request = new PricesByIntervalsRequest(HttpRequests::Requests());
    $request->fk_branch_id=Authorization::getBranchCode();
    $obj =  new CreatePriceIntervalUserCase(new PriceRepository());
    $createAgreementUseCase =$obj; 
    $result=$createAgreementUseCase->execute(new PricesIntervalsDto($request->toArray()));
    new ResponseJson(HttpStatus::$HTTP_CODE_CREATED, $result);
} catch (Exception $e) {
    new ResponseJson($e->getCode(), $e->getMessage());
}


