<?php

use Commons\HttpRequests;
use Resources\Strings;
use Commons\ResponseJson;
use Resources\HttpStatus;
use Middleware\Authorization;
use Repositories\MonthlyPayers\MonthlyPayersRepository;
use Requests\IdRequest;
use UseCases\Monthlypayers\DisableMonthlySubscriptionUseCase;

require_once __DIR__."./../../core/Settings.php";
try {
    Authorization::Init();
    $request =  new IdRequest(HttpRequests::Requests());
    $disableMonthlySubscriptionUseCase =  new DisableMonthlySubscriptionUseCase(new MonthlyPayersRepository());
    if($disableMonthlySubscriptionUseCase->execute($request->id)){
        new ResponseJson(HttpStatus::$HTTP_CODE_OK,Strings::$STR_REGISTER_UPDATE_COM_SUCESS);
    }
    new ResponseJson(HttpStatus::$HTTP_CODE_BAD_REQUEST,Strings::$STR_REGISTER_UPDATE_COM_NOT_SUCESS);
} catch (Exception $e) {
    new ResponseJson($e->getCode(), $e->getMessage());
}
