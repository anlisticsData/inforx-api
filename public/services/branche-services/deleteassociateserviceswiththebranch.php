<?php
use Commons\Uteis;
use Resources\Strings;
use Commons\HttpRequests;
use Commons\ResponseJson;
use Resources\HttpStatus;
use Requests\ServiceRequest;
use Middleware\Authorization;
use Services\BranchesServices;
use Requests\ServiceBrancheRequest;
require_once __DIR__."./../../../core/Settings.php";
try{
    Authorization::Init();
    $request = new ServiceBrancheRequest(HttpRequests::Requests());
    $BranchesServices =  new BranchesServices();
    $customer = $BranchesServices->SearchByCustomer($request->code_branche,Authorization::CustomerId());
    $service = $BranchesServices->serviceToCodeAndCustomer($request->code_service,Authorization::CustomerId());
    if(is_null($customer) || count($customer)==0){
        throw new Exception(Strings::$STR_SERVICE_BRANCHE_COSTUMER_NOT_FOUND,HttpStatus::$HTTP_CODE_BAD_REQUEST);
    }
    if(is_null($service) || count($service)==0){
        throw new Exception(Strings::$STR_BRANCHES_COSTUMER_SERVICE_CODE_NOT_FOUND,HttpStatus::$HTTP_CODE_BAD_REQUEST);
    }
    if($BranchesServices->deleteAssociateServicesWithTheBranch($service[0],$customer[0]) > 0){
        new ResponseJson(HttpStatus::$HTTP_CODE_OK,Strings::$STR_BRANCHES_COSTUMER_SERVICE_ASSOCIATION_IS_DELETED);
    }
    new ResponseJson(HttpStatus::$HTTP_CODE_NOT_MODIFIED,Strings::$STR_BRANCHES_COSTUMER_SERVICE_NOT_DELETED);
}catch(Exception $e){
    new ResponseJson($e->getCode(),$e->getMessage());
}