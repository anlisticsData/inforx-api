<?php
use Commons\HttpRequests;
use Commons\ResponseJson;
use Services\StateServices;

require_once __DIR__."./../../core/Settings.php";
try{
    $StateServices =  new StateServices();
    new ResponseJson(200,$StateServices->All());
}catch(Exception $e){
    new ResponseJson($e->getCode(),$e->getMessage());
}