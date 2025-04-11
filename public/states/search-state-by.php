<?php
use Commons\HttpRequests;
use Commons\ResponseJson;
use Requests\SearchStateByRequest;
use Resources\HttpStatus;
use Services\StateServices;

require_once __DIR__."./../../core/Settings.php";
try{
    $CityServices =  new StateServices();
    $request =  new SearchStateByRequest(HttpRequests::requestGET());
    new ResponseJson(HttpStatus::$HTTP_CODE_OK,$CityServices->By($request->stateid));
}catch(Exception $e){
    new ResponseJson($e->getCode(),$e->getMessage());
}