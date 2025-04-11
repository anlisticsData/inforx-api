<?php

use Commons\Clock;
use Commons\HttpRequests;
use Commons\Uteis;
use Commons\ResponseJson;
use Resources\HttpStatus;
use Middleware\Authorization;
use Repositories\Car\MonthlyRepository;
use UseCases\Totalizers\CloseOfDayuseCase;
use Repositories\Movements\MovementsRepository;

require_once __DIR__ . "./../../core/Settings.php";
try {
    Authorization::Init();
    $request =  HttpRequests::Init();
    $data = $request['date']??null;
    $formart = $request['formart']??null;
    if(is_null($data)){
       $data =Clock::NowDate();
    }
    if(is_null($formart)){
        $formart = 'Y-m-d H:i:s';
    }
    $CloseOfDayuseCase = new CloseOfDayuseCase(new MovementsRepository(),new MonthlyRepository());
    new ResponseJson(HttpStatus::$HTTP_CODE_OK,$CloseOfDayuseCase->execute($data,$formart));
} catch (Exception $e) {
    new ResponseJson($e->getCode(), $e->getMessage());
}
