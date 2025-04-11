<?php

use Commons\Uteis;
use Commons\Uteiss;
use Resources\Strings;
use Commons\HttpRequests;
use Commons\ResponseJson;
use Resources\HttpStatus;
use Middleware\Authorization;
use Services\BranchesServices;
use Requests\ServiceDeleteRequest;
require_once __DIR__."./../../core/Settings.php";
try{
    Authorization::Init();
    
    $request = new ServiceDeleteRequest(HttpRequests::Requests());
    $BranchesServices =  new BranchesServices();
    if($BranchesServices->serviceBy($request->id,$request->customer_id) > 0){
        new ResponseJson(HttpStatus::$HTTP_CODE_OK,Strings::$STR_BRANCHES_COSTUMER_SERVICE_IS_DELETED);
    }
    new ResponseJson(HttpStatus::$HTTP_CODE_NOT_MODIFIED,Strings::$STR_BRANCHES_COSTUMER_SERVICE_NOT_DELETED);
}catch(Exception $e){
    new ResponseJson($e->getCode(),$e->getMessage());
}

