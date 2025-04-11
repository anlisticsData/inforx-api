<?php

use Commons\Uteis;
use Commons\HttpRequests;
use Commons\ResponseJson;
use Resources\HttpStatus;
use Requests\PlateRequest;
use Middleware\Authorization;
use UseCases\GetTypeAllUserCase;
use Repositories\Car\CartypeRepository;
use Repositories\Car\MonthlyRepository;
use UseCases\Cartypes\GetByPlateUseCase;

require_once __DIR__."./../../../core/Settings.php";
try{
   Authorization::Init();
   $request =  new PlateRequest(HttpRequests::Requests());
   $brand = Authorization::getBranchCode();
   $GetByPlateUseCase =  new GetByPlateUseCase(new MonthlyRepository());
   $resultPlateUseCase = $GetByPlateUseCase->execute($request->plate,$brand);
   if(count($resultPlateUseCase) > 0){
    new ResponseJson(HttpStatus::$HTTP_CODE_OK,  $resultPlateUseCase[0]);
   }
   new ResponseJson(HttpStatus::$HTTP_CODE_OK,null);
}catch(Exception $e){
    new ResponseJson($e->getCode(),$e->getMessage());
}

