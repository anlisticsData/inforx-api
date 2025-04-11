<?php

use Commons\ResponseJson;
use Resources\HttpStatus;
use Middleware\Authorization;
use Repositories\Car\CartypeRepository;
use Repositories\Colors\ColorsRepository;
use UseCases\Colors\GetColorsAllUserCase;
 
 
require_once __DIR__."./../../core/Settings.php";
try{
   //Authorization::Init();
  
   $GetCartypeUseCase =  new GetColorsAllUserCase(new ColorsRepository());
   $output=$GetCartypeUseCase ->execute();
   new ResponseJson(HttpStatus::$HTTP_CODE_OK,$output);
}catch(Exception $e){
    new ResponseJson($e->getCode(),$e->getMessage());
}

