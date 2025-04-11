<?php

use Commons\HttpRequests;
use Commons\ResponseJson;
use Middleware\Authorization;
use Dtos\MonthlyCartypeAssociateDto;
use Repositories\Car\MonthlyRepository;
use Requests\MonthlyCartypeAssociateRequest;
use Repositories\MonthlyPayers\MonthlyPayersRepository;
use Resources\HttpStatus;
use Resources\Strings;
use UseCases\Monthlypayers\AssociateCarWithMonthlyUseCase;

 
require_once __DIR__."./../../core/Settings.php";
try {
   
    Authorization::Init();
    $userData = Authorization::playload();
    $request =  new MonthlyCartypeAssociateRequest(HttpRequests::Requests());
    $request->fk_branch = $userData["branch"];
    $request->fk_curtomers = $userData["customer"];
    $AssociateCarWithMonthlyUseCase =  new AssociateCarWithMonthlyUseCase(new MonthlyPayersRepository(),new MonthlyRepository());
    if($AssociateCarWithMonthlyUseCase->execute(new MonthlyCartypeAssociateDto($request->toArray()))){
        new ResponseJson(HttpStatus::$HTTP_CODE_CREATED,Strings::$STR_REGISTER_CREATED_COM_SUCESS);
    }
    new ResponseJson(HttpStatus::$HTTP_CODE_BAD_REQUEST,Strings::$STR_REGISTER_CREATED_COM_NOT_SUCESS);
} catch (Exception $e) {
    new ResponseJson($e->getCode(), $e->getMessage());
}
