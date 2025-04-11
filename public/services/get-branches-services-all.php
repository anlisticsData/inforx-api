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
use Requests\PaginatorRequestParams;
use Requests\PaginatorRequestBrancheServices;
use Requests\PaginatorRequestBrancheServicesAll;
 

require_once __DIR__."./../../core/Settings.php";


try{
    Authorization::Init();
    $request = new PaginatorRequestParams();
    $UserServices =  new UserServices();
    $BranchesServices =  new BranchesServices();
    $branches =  $BranchesServices->SearchByServicesToCodeORNameOrDescription($request->params);
     new ResponseJson(200, $branches);
}catch(Exception $e){
    new ResponseJson($e->getCode(),$e->getMessage());
}

