<?php

use Dtos\BoxDto;
use Resources\Strings;
use Requests\BoxRequest;
use Commons\HttpRequests;
use Commons\ResponseJson;
use Commons\Uteis;
use Resources\HttpStatus;
use Middleware\Authorization;
use Repositories\Box\BoxRepository;
use UseCases\Box\CloseTheCashRegisterUseCase;
use UseCases\Box\CheckIfTheBoxHasBeenOpenedUseCase;
 

require_once __DIR__."./../../core/Settings.php";
try{

    Authorization::Init();
    $user =  Authorization::playload();
    $request =  new BoxRequest(HttpRequests::Requests());
    if(!is_numeric($request->amount)){
        throw new Exception(Strings::$APP_PRINCES_INPUT_VALUE_INVALID,HttpStatus::$HTTP_CODE_BAD_REQUEST);
    }
    $checkIfTheBoxHasBeenOpenedUseCase =new CheckIfTheBoxHasBeenOpenedUseCase(new BoxRepository());
    $isOpenBox = $checkIfTheBoxHasBeenOpenedUseCase->execute(Authorization::getBranchCode(),true);
    if(!is_null($isOpenBox)){
        $CloseTheCashRegisterUseCase =  new CloseTheCashRegisterUseCase(new BoxRepository());
        $result = $CloseTheCashRegisterUseCase->execute(new BoxDto([
            "id"=>$isOpenBox->id,
            "amount"=>$request->amount,
            "users_id"=>Authorization::getUserCode(),
            "branches_id"=>Authorization::getBranchCode()
        ]));

        if($result > 0){
            new ResponseJson(HttpStatus::$HTTP_CODE_CREATED,$result);
        }
        new ResponseJson(HttpStatus::$HTTP_CODE_NOT_MODIFIED);
    }
}catch(Exception $e){
    new ResponseJson($e->getCode(),$e->getMessage());
}

