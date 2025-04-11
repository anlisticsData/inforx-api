<?php

use Commons\HttpRequests;
use Commons\ResponseJson;
use Middleware\Authorization;
use Requests\PaymentOfMonthlyPaymentsRequest;

 

require_once __DIR__."./../../core/Settings.php";
try{
    Authorization::Init();
    $request =  new PaymentOfMonthlyPaymentsRequest(HttpRequests::requestJSON());
    $playload = Authorization::playload();
    
    new ResponseJson(201,$request);
  
}catch(Exception $e){
    new ResponseJson($e->getCode(),$e->getMessage());
}

