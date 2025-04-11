<?php
use Commons\HttpRequests;
use Commons\ResponseJson;
use Services\CityServices;
use Requests\SearchCitiesByStateRequest;
require_once __DIR__."./../../core/Settings.php";
try{
    $CityServices =  new CityServices();
    $request =  new SearchCitiesByStateRequest();
    new ResponseJson(200,$CityServices->SearchCitiesByState($request->stateid));
}catch(Exception $e){
    new ResponseJson($e->getCode(),$e->getMessage());
}


 