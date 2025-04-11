<?php

use Commons\HttpRequests;
use Commons\ResponseJson;
use Commons\Uteis;
use Resources\HttpStatus;
use Middleware\Authorization;
use Requests\HasNewPlateInput;
use Requests\PaymentsExitCarRequest;
use Repositories\Movements\MovementsRepository;
use UseCases\Movements\RedeemOpenPlatesOfTheDay;

 
require_once "../../../core/Settings.php";
require_once __DIR__ . "./../../../core/Settings.php";
require_once __DIR__."./../../../mocks/MockModuleRepository.php";
try {
    Authorization::Init();
    $request = new PaymentsExitCarRequest(HttpRequests::Requests());
    $RedeemOpenPlatesOfTheDay =  new RedeemOpenPlatesOfTheDay(new MovementsRepository());
    $outResponse = $RedeemOpenPlatesOfTheDay->execute($request->uuid); 
    new ResponseJson(HttpStatus::$HTTP_CODE_OK, $outResponse);
} catch (Exception $e) {
    new ResponseJson($e->getCode(), $e->getMessage());
}
