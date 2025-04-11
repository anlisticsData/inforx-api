<?php
use Resources\Strings;
use Requests\BoxRequest;
use Commons\HttpRequests;
use Commons\ResponseJson;
use Dtos\BoxDto;
use Resources\HttpStatus;
use Middleware\Authorization;
use Repositories\Box\BoxRepository;
use UseCases\Box\OpenTheCashRegisterUseCase;
use UseCases\Box\CheckIfTheBoxHasBeenOpenedUseCase;

require_once __DIR__."./../../core/Settings.php";
try{
    Authorization::Init();
    $request =  new BoxRequest(HttpRequests::Requests());
    $checkIfTheBoxHasBeenOpenedUseCase =new CheckIfTheBoxHasBeenOpenedUseCase(new BoxRepository());
    if(!$checkIfTheBoxHasBeenOpenedUseCase->execute(Authorization::getBranchCode())){
        $OpenTheCashRegisterUseCase =  new OpenTheCashRegisterUseCase(new BoxRepository());
        $result = $OpenTheCashRegisterUseCase->execute(new BoxDto([
            "amount"=>floatval($request->amount),
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

