<?php

use Commons\Uteis;
use Resources\Strings;
use Commons\HttpRequests;
use Commons\ResponseJson;
use Resources\HttpStatus;
use Middleware\Authorization;
use Services\BranchesServices;
use Requests\SearchBranchesByRequest;

require_once __DIR__."./../../core/Settings.php";
try {
    Authorization::Init();

    $request = new SearchBranchesByRequest(HttpRequests::Requests());
    $branchesServices = new BranchesServices();
    $branch_id = $request->id;
   
    $branchesExists = $branchesServices->CheckExistsBranches($branch_id);
    if (!$branchesExists) {
        new ResponseJson(HttpStatus::$HTTP_CODE_BAD_REQUEST,Strings::$STR_BRANCHES_COSTUMER_NOT_FOUND);
    }
    $branch = $branchesServices->SearchBy($branch_id);
    if(is_array($branch) &&  count($branch) > 0){
        new ResponseJson(HttpStatus::$HTTP_CODE_OK,$branch[0]);
    }
    new ResponseJson(HttpStatus::$HTTP_CODE_OK,"adasda");
} catch (Exception $e) {
    new ResponseJson($e->getCode(),$e->getMessage());
}
