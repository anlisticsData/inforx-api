<?php

use Commons\HttpRequests;
use Commons\ResponseJson;
use Middleware\Authorization;
use Requests\SearchBranchesByCostumers;
use Resources\HttpStatus;
use Services\BranchesServices;
use Services\CustomerServices;

require_once __DIR__."./../../core/Settings.php";
try {
    //Authorization::Init();

    $request = new SearchBranchesByCostumers(HttpRequests::Requests());
    $branchesServices = new BranchesServices();
    $costumersServices = new CustomerServices();

    $costumer_id = $request->costumerid;
    $costumerExists = $costumersServices->CheckExistsCostumer($costumer_id);

    if (!$costumerExists) {
        new ResponseJson(HttpStatus::$HTTP_CODE_BAD_REQUEST,null);
    }

    $branch = $branchesServices->SearchByCostumer($costumer_id);
    new ResponseJson(HttpStatus::$HTTP_CODE_OK,$branch);
} catch (Exception $e) {
    new ResponseJson($e->getCode(),$e->getMessage());
}
