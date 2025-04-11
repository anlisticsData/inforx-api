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
use Middleware\Authorization;
use Services\BranchesServices;

require_once __DIR__."./../../core/Settings.php";
try{
    
    Authorization::Init();
    $playload = Authorization::Token();
    $request = new BanchRequest(HttpRequests::Requests());
    $branchesServices = new BranchesServices();
    $userServices = new UserServices();
    $isBranch = $branchesServices->SearchBranchCnpjBy($request->cnpj);
    if(is_null($isBranch)){
        $user = $userServices->by($playload["user"]);
        $branch = new BranchesDto($request);
        $branch->id = $request->id;
        $branch->costumers_id = $user->customer_id;
        $branch->description = $request->description;
        $branch->cnpj = $request->cnpj;
        $branch->phone = $request->phone;
        $branch->email = $request->email;
        $branch->address =$request->address;
        $branch->available_vacancies =$request->available_vacancies;
        $branch->insurance_expiration =$request->insurance_expiration;
        $branch->text_ticket =$request->text_ticket;
        $branch->status =1;
        $branch->city =$request->city;
        $branch->state = $request->state;
        $branch->free_time = $request->free_time;
        $branch->insurance_expiration = $request->insurance_expiration;
        $branch->avatar_id = $request->avatar_id;
        $result = $branchesServices->created($branch);
        if(!is_null($request)){
            new ResponseJson(HttpStatus::$HTTP_CODE_CREATED,$branchesServices->SearchBy($result));
        }
    }
    new ResponseJson(HttpStatus::$HTTP_CODE_NOT_ACCEPTABLE,Strings::$STR_BRANCHES_COSTUMER_NOT_CREATED);
}catch(Exception $e){
    new ResponseJson($e->getCode(),$e->getMessage());
}

