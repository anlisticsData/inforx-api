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


require_once __DIR__."./../../core/Settings.php";
try{

 
    Authorization::Init();
    $request = new ServiceRequest(HttpRequests::Requests());
    $request->customer_id = Authorization::CustomerId();
    $BranchesServices =  new BranchesServices();
    $result = $BranchesServices->createService($request);
    if(is_null($result)){
        throw new Exception(Strings::$STR_BRANCHES_COSTUMER_SERVICE_NOT_FOUND,HttpStatus::$HTTP_CODE_BAD_REQUEST);
    }
    new ResponseJson(HttpStatus::$HTTP_CODE_CREATED,$result);
}catch(Exception $e){
    new ResponseJson($e->getCode(),$e->getMessage());
}

