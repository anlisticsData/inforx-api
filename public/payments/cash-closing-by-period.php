<?php

use Commons\Clock;
use Commons\HttpRequests;
use Commons\ResponseJson;
use Commons\Uteis;
use Resources\HttpStatus;
use Requests\PeriodRequest;
use Middleware\Authorization;
use Repositories\Car\MonthlyRepository;
use UseCases\Totalizers\CloseOfDayuseCase;
use UseCases\Payments\ResumeBoxPeriodUseCase;
use Repositories\Movements\MovementsRepository;
use Repositories\Agreements\AgreementRepository;
use Repositories\Payments\PaymentTypesRepository;
use Repositories\Payments\ResumeBoxPeriodRepository;

 
require_once __DIR__."./../../core/Settings.php";
try{
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
    $result=$CloseOfDayuseCase->execute($data,$formart);
    $response=[
        "total" => [
            "entries" =>$result['vehicles'],
            "releases" =>$result['totalRecebido'] ,
            "discounts" =>number_format($result['totalDescontos'],2),
            "liquid" =>number_format($result['totalRecebido'] - $result['totalDescontos'],2)
        ],
        "summary" =>[],
        "summary_disconts" => []
    ];

    new ResponseJson(HttpStatus::$HTTP_CODE_OK,$response);
}catch(Exception $e){

    new ResponseJson($e->getCode(),$e->getMessage());
}

