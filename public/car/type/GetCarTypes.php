<?php

use Commons\HttpRequests;
use Commons\ResponseJson;
use Resources\HttpStatus;
use Middleware\Authorization;
use UseCases\GetTypeAllUserCase;
use Repositories\Car\CartypeRepository;
 
require_once __DIR__."./../../../core/Settings.php";
try{
   Authorization::Init();
   $GetCartypeUseCase =  new GetTypeAllUserCase(new CartypeRepository());
   $output=$GetCartypeUseCase ->execute();
   new ResponseJson(HttpStatus::$HTTP_CODE_OK,$output);
}catch(Exception $e){
    new ResponseJson($e->getCode(),$e->getMessage());
}

