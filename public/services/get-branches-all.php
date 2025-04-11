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
use Requests\PaginatorRequestBrancheServicesAll;
 

require_once __DIR__."./../../core/Settings.php";


try{
  
    Authorization::Init();
    $request = new PaginatorRequestBrancheServicesAll();
    $UserServices =  new UserServices();
    $BranchesServices =  new BranchesServices();
    $user =  $UserServices->by(Authorization::Token()['user']);
    $branches =  $BranchesServices->SearchByCustomerBy($user->customer_id);
    if(is_null($branches) || count($branches)==0){
        throw new Exception(Strings::$STR_BRANCHES_COSTUMER_NOT_FOUND,HttpStatus::$HTTP_CODE_BAD_REQUEST);
    }
     new ResponseJson(200, $branches);
}catch(Exception $e){
    new ResponseJson($e->getCode(),$e->getMessage());
}

