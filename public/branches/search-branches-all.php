<?php

use Models\User;
 
use Commons\Uteis;
 
use Dtos\CustomerDto;
use Resources\Strings;
use Commons\HttpRequests;
use Commons\ResponseJson;
use Resources\HttpStatus;
use Resources\APPLICATION;
use Services\UserServices;
use Middleware\Authorization;
use Requests\BanchRequest;
use Services\BranchesServices;
use Dtos\BranchesDto;

require_once __DIR__."./../../core/Settings.php";
try{
    
    //Authorization::Init();
    $request = new BanchRequest(HttpRequests::Requests());
    $pager = (!isset($request->pager) || strlen($request->pager)==0) ? 1 : $request->pager;
    $branchesServices = new BranchesServices();
    $branchDto =  new BranchesDto();
    $branchDto->search= $request->search."%";
    $branches = $branchesServices->all($branchDto,$pager);
   	new ResponseJson(200,$branches);
    
}catch(Exception $e){
    new ResponseJson($e->getCode(),$e->getMessage());
}

