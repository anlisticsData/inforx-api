<?php

use Commons\HttpRequests;
use Commons\ResponseJson;
use Resources\HttpStatus;
use Dtos\PricesIntervalsDto;
use Middleware\Authorization;
use Repositories\Price\PriceRepository;
use Requests\AllPricesIntervalsRequestType;
use UseCases\Price\DeletePriceIntervalUserCase;



require_once __DIR__ . "./../../core/Settings.php";
try {
    Authorization::Init();
    $request = new AllPricesIntervalsRequestType(HttpRequests::Requests(),Authorization::getBranchCode());
    $obj =  new DeletePriceIntervalUserCase(new PriceRepository());
    $createAgreementUseCase =$obj; 
    $result=$createAgreementUseCase->execute(new PricesIntervalsDto($request->toArray()));
    new ResponseJson(HttpStatus::$HTTP_CODE_NO_CONTENT, $result);
} catch (Exception $e) {
    new ResponseJson($e->getCode(), $e->getMessage());
}
