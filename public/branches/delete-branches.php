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
use Requests\DeleteRequest;
use Middleware\Authorization;
use Services\BranchesServices;
use Requests\UserUpdateBranchRequest;

require_once __DIR__."./../../core/Settings.php";
try{

    
   
    Authorization::Init();
    $request = new DeleteRequest(HttpRequests::Requests());
    $branchesServices = new BranchesServices();
    if(!is_null($branchesServices->deleteBranche($request->id))){
        new ResponseJson(HttpStatus::$HTTP_CODE_OK,Strings::$STR_REGISTRO_IS_DELETED);
    }
    new ResponseJson(HttpStatus::$HTTP_CODE_NOT_MODIFIED,Strings::$STR_REGISTRO_NOT_IS_DELETED);
   
}catch(Exception $e){
    new ResponseJson($e->getCode(),$e->getMessage());
}

