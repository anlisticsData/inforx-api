<?php

use Commons\Uteis;
use Commons\Paginator;
use Commons\ResponseJson;
use Commons\StringBuilder;
use Services\CityServices;
use Requests\PaginatorRequest;

require_once __DIR__."./../../core/Settings.php";


try{
    $request = new PaginatorRequest();
    
    $CityServices =  new CityServices();
    $response=$CityServices->SearchCitiesAll($request->pager);
    
    new ResponseJson(200,$response);
}catch(Exception $e){
    new ResponseJson($e->getCode(),$e->getMessage());
}

