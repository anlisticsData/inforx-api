<?php

use Commons\ResponseJson;
use Middleware\Authorization;
use UseCases\Modules\LocalhostPrintUseCase;
use Repositories\Movements\MovementsRepository;
require_once __DIR__."./../../core/Settings.php";
try {
    Authorization::Init();
    $localhostPrintUseCase =  new LocalhostPrintUseCase(new MovementsRepository());
    new ResponseJson(200,$localhostPrintUseCase->execute(Authorization::getBranchCode()));
   } catch (Exception $e) {
    new ResponseJson($e->getCode(), $e->getMessage());
}
