<?php

use Commons\Uteis;
use Requests\IdRequest;
use Commons\HttpRequests;
use Commons\ResponseJson;
use Resources\HttpStatus;
use Middleware\Authorization;
use Repositories\MonthlyPayers\MonthlyPayersRepository;
use UseCases\Monthlypayers\GetAllCarsMonthlyPayerUseCase;

require_once __DIR__."./../../core/Settings.php";
try {
   Authorization::Init();
   $request   =  new IdRequest(HttpRequests::Requests());
   $getAllCarsMonthlyPayerUseCase=new GetAllCarsMonthlyPayerUseCase(new MonthlyPayersRepository());
   new ResponseJson(HttpStatus::$HTTP_CODE_OK,$getAllCarsMonthlyPayerUseCase->execute($request->id));
} catch (Exception $e) {
    new ResponseJson($e->getCode(), $e->getMessage());
}
