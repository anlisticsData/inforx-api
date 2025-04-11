<?php

use Resources\Strings;
use Commons\ResponseJson;
use Resources\HttpStatus;
use Middleware\Authorization;
use Repositories\Box\BoxRepository;
use UseCases\Box\CheckIfTheBoxHasBeenOpenedUseCase;
 
require_once __DIR__."./../../core/Settings.php";
try{
    Authorization::Init();
    $checkIfTheBoxHasBeenOpenedUseCase =new CheckIfTheBoxHasBeenOpenedUseCase(new BoxRepository());
    if(!$checkIfTheBoxHasBeenOpenedUseCase->execute(Authorization::getBranchCode())){
        throw new Exception(Strings::$STR_OPEN_BOX__INVALID,HttpStatus::$HTTP_CODE_BAD_REQUEST);
    }
   
}catch(Exception $e){
    new ResponseJson($e->getCode(),$e->getMessage());
}

