<?php

use Commons\ResponseJson;
use Resources\HttpStatus;
use Middleware\AuthorizationResources;

require_once __DIR__."./../../core/Settings.php";
try{
    AuthorizationResources::Init();
}catch(Exception $e){
    new ResponseJson($e->getCode(),$e->getMessage());
}