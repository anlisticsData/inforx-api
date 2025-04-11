<?php

use Commons\HttpRequests;
use Commons\ResponseJson;
use Requests\TypeCarRequest;
use Middleware\Authorization;
use Services\CartypeServices;
require_once __DIR__."./../../../core/Settings.php";
try{
   Authorization::Init();
   $request = new TypeCarRequest(HttpRequests::Requests());
   $CartypeServices =  new CartypeServices();
   



}catch(Exception $e){
    new ResponseJson($e->getCode(),$e->getMessage());
}

