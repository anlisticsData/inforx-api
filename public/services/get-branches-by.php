<?php

use Commons\Uteis;
use Commons\Paginator;
use Resources\Strings;
use Commons\ResponseJson;
use Resources\HttpStatus;
use Resources\APPLICATION;
use Services\UserServices;
use Middleware\Authorization;
use Requests\PaginatorRequest;
use Services\BranchesServices;
use Requests\PaginatorRequestBrancheServices;
require_once __DIR__."./../../core/Settings.php";
try{
    Authorization::Init();
    $request = new PaginatorRequestBrancheServices();
    $UserServices =  new UserServices();
    $BranchesServices =  new BranchesServices();
    $user =  $UserServices->by(Authorization::Token()['user']);
    $branche =  $BranchesServices->SearchByCustomer($request->branche,$user->customer_id);
    if(count($branche)==0){
        throw new Exception(Strings::$STR_BRANCHES_COSTUMER_NOT_FOUND,HttpStatus::$HTTP_CODE_BAD_REQUEST);
    }
    $response = $BranchesServices->services($branche[0]->id);
    new ResponseJson(200,$response);
}catch(Exception $e){
    new ResponseJson($e->getCode(),$e->getMessage());
}

