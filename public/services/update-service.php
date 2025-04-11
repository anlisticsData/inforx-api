<?php

use Models\User;
 
use Commons\Uteis;
 
use Dtos\BranchesDto;
use Dtos\CustomerDto;
use Resources\Strings;
use Commons\HttpRequests;
use Commons\ResponseJson;
use Resources\HttpStatus;
use Requests\BanchRequest;
use Resources\APPLICATION;
use Services\UserServices;
use Requests\ServiceRequest;
use Middleware\Authorization;
use Services\BranchesServices;
use Requests\ServiceUpdateRequest;

require_once __DIR__."./../../core/Settings.php";
try{
    Authorization::Init();
    $request = new ServiceUpdateRequest(HttpRequests::Requests());
    $BranchesServices =  new BranchesServices();
    $result =  $BranchesServices->updateService($request);
    if(is_null($result)){
        throw new Exception(Strings::$STR_BRANCHES_COSTUMER_SERVICE_NOT_UPDATE,HttpStatus::$HTTP_CODE_NOT_MODIFIED);
    }
    new ResponseJson(HttpStatus::$HTTP_CODE_CREATED,Strings::$STR_BRANCHES_COSTUMER_SERVICE_IS_UPDATE);
}catch(Exception $e){
    new ResponseJson($e->getCode(),$e->getMessage());
}

