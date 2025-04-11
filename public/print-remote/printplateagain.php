<?php

use Commons\HttpRequests;
use Commons\ResponseJson;
use Commons\Uteis;
use Middleware\Authorization;
use UseCases\Modules\LocalhostPrintUseCase;
use Repositories\Movements\MovementsRepository;
use Requests\IdRequest;
use UseCases\Modules\LocalhostPrintProcessedUseCase;

require_once __DIR__."./../../core/Settings.php";
try {
    Authorization::Init();
    $request =  new IdRequest(HttpRequests::Requests());
    $movementsRepository = new MovementsRepository();
    new ResponseJson(200, $movementsRepository->printReset($request->id));
   } catch (Exception $e) {
    new ResponseJson($e->getCode(), $e->getMessage());
}
