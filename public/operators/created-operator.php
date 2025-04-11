<?php

use Commons\Uteis;
use Dtos\OperatorDto;
use Resources\Strings;
use Commons\HttpRequests;
use Commons\ResponseJson;
use Resources\HttpStatus;
use Services\UserServices;
use Requests\OperatorRequest;
use Commons\DataBaseRepository;
use Repositories\Users\UserRepository;
use UseCases\Operators\CreatedOperatorUserCase;
require_once __DIR__."./../../core/Settings.php";
try{
    $request =  new OperatorRequest(HttpRequests::requestJSON());
    $ServiceUser =  new UserServices();
    $CreatedOperatorUserCase =  new CreatedOperatorUserCase(new UserRepository(new DataBaseRepository()));
    $OperatorDto =  new OperatorDto($request->toArray());

    $ServiceUser->CheckUserExists($OperatorDto->email,$OperatorDto->password);

    
    $result = $CreatedOperatorUserCase->execute($OperatorDto);
    if(!is_null($result)){
        new ResponseJson(HttpStatus::$HTTP_CODE_CREATED,$result);
    }
    new ResponseJson(HttpStatus::$HTTP_CODE_BAD_REQUEST,Strings::$STR_USER_CREATED_COM_NOT_SUCESS);
}catch(Exception $e){
    new ResponseJson($e->getCode(),$e->getMessage());
}

