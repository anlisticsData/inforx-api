<?php

use Commons\Uteis;
use Commons\HttpRequests;
use Commons\ResponseJson;
use Dtos\PixCodeDto;
use Resources\HttpStatus;
use Requests\PixCodeRequest;
use Requests\ServiceRequest;
use Middleware\Authorization;
use Repositories\Settings\SettingRepository;
use UseCases\Settings\CreatePixSettingUseCase;

require_once __DIR__."./../../core/Settings.php";
try{
    Authorization::Init();
    $request = new PixCodeRequest(HttpRequests::Requests());
    $CreatePixSettingUseCase =  new CreatePixSettingUseCase(new SettingRepository());
    $resultData=$CreatePixSettingUseCase->execute(new PixCodeDto($request->toArray()));
    new ResponseJson(HttpStatus::$HTTP_CODE_CREATED,$resultData);
}catch(Exception $e){
    new ResponseJson($e->getCode(),$e->getMessage());
}

