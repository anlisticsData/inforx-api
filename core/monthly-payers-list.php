<?php

use Commons\Uteis;
use Commons\HttpRequests;
use Commons\ResponseJson;
use Dtos\MonthlyPayerDto;
use Dtos\MonthyPlateDto;
use Resources\HttpStatus;
use Middleware\Authorization;
use PharIo\Manifest\Application;
use Requests\MonthlyPayerRequest;
use Repositories\Branches\BranchesRepository;
use Repositories\Car\MonthlyRepository;
use UseCases\Monthlypayers\MonthlyPayerAllUseCase;
use UseCases\Monthlypayers\MonthlyPayerCreatedUseCase;
use Repositories\MonthlyPayers\MonthlyPayersRepository;
use UseCases\Monthlypayers\ReserveAPlaceForTheMonthlyMemberUseCase;

require_once __DIR__."./../../core/Settings.php";
try {
   
    Authorization::Init();
    $userData = Authorization::playload();
    $MonthlyPayerAllUseCase =  new MonthlyPayerAllUseCase(new MonthlyPayersRepository());
    new ResponseJson(HttpStatus::$HTTP_CODE_OK,$MonthlyPayerAllUseCase->execute(new MonthlyPayerDto([
        "fk_curtomers"=>$userData["customer"],
        "fk_branch"=>$userData["branch"]
    ])));
} catch (Exception $e) {
    new ResponseJson($e->getCode(), $e->getMessage());
}
