<?php
use Commons\HttpRequests;
use Commons\ResponseJson;
use Services\CityServices;
use Requests\SearchStatePaginator;

require_once __DIR__."./../../core/Settings.php";
try{
    $CityServices =  new CityServices();
    $request = new SearchStatePaginator(HttpRequests::Requests());
    
    new ResponseJson(200,$CityServices->SearchCitiesStatePaginator($request->stateid, $request->pager));
}catch(Exception $e){
    new ResponseJson($e->getCode(),$e->getMessage());
}


 