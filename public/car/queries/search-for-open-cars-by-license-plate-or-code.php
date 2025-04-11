<?php

use Models\IsOpenCar;
use Commons\HttpRequests;
use Commons\ResponseJson;
use Resources\HttpStatus;
use Middleware\Authorization;
use Requests\IsOpenCarRequest;
use Repositories\Movements\MovementsRepository;
 

require_once "../../../core/Settings.php";
require_once __DIR__ . "./../../../core/Settings.php";


require_once __DIR__."./../../../mocks/MockModuleRepository.php";





try {
    Authorization::Init();
    $request = new IsOpenCarRequest(HttpRequests::Requests());
    $r =  new MovementsRepository();
    
    new ResponseJson(HttpStatus::$HTTP_CODE_OK,  $r->isOpenCarPlateOrCode(new IsOpenCar([
      "plateOrCode"=>$request->plateOrCode,
      "branche"=>$request->branche
    ])));

} catch (Exception $e) {
    new ResponseJson($e->getCode(), $e->getMessage());
}
