<?php
use Commons\Uteis;
use Dtos\OperatorDto;
use Resources\Strings;
use Commons\HttpRequests;
use Commons\ResponseJson;
use Resources\HttpStatus;
use Services\UserServices;
use Middleware\Authorization;
use Requests\OperatorRequest;
use Commons\DataBaseRepository;
use UseCases\Price\ByPriceUserCase;
use Requests\PaymentsExitCarRequest;
use Repositories\Users\UserRepository;
use Repositories\Car\CartypeRepository;
use Repositories\Price\PriceRepository;
use UseCases\Price\PriceActiveUserCase;
use UseCases\Cartypes\TypeOfVehiclesUseCase;
use Repositories\Payments\PaymentsRepository;
use Repositories\Movements\MovementsRepository;
use UseCases\Operators\CreatedOperatorUserCase;
use UseCases\Payments\CheckMonthlyPaymentUseCase;
use Repositories\MonthyPlate\MonthyPlateRepository;
use UseCases\Payments\CalculateLengthOfStayUseCase;
use UseCases\MonthyPlates\ByUuidMonthPlatesUserCase;
use UseCases\Payments\InformationOfPaymentsByPlateUseCase;
require_once __DIR__."./../../core/Settings.php";
try{
   
    Authorization::Init();
    $request =  new PaymentsExitCarRequest(HttpRequests::requestJSON());

    $CalculateLengthOfStayUseCase  = new CalculateLengthOfStayUseCase(
        new MovementsRepository(),
        new PriceActiveUserCase(new PriceRepository()),
        new ByUuidMonthPlatesUserCase(new MonthyPlateRepository()),
        new CheckMonthlyPaymentUseCase(new PaymentsRepository()),
        new PriceRepository(),
        new CartypeRepository()
    );

    $CalculateLengthOfStayUseCaseResult = $CalculateLengthOfStayUseCase->execute($request->uuid,Authorization::getBranchCode());
    $InformationOfPaymentsByPlateUseCase =  new InformationOfPaymentsByPlateUseCase(
            new MovementsRepository(),
            new PriceActiveUserCase(new PriceRepository()),
            new ByUuidMonthPlatesUserCase(new MonthyPlateRepository()),
            new CheckMonthlyPaymentUseCase(new PaymentsRepository())
        );

    $InformationOfPaymentsByPlateUseCaseResult = $InformationOfPaymentsByPlateUseCase->execute($request->uuid);
    $InformationOfPaymentsByPlateUseCaseResult->movement->permanence=$CalculateLengthOfStayUseCaseResult->time;
    $InformationOfPaymentsByPlateUseCaseResult->total=$CalculateLengthOfStayUseCaseResult->total;
    $InformationOfPaymentsByPlateUseCaseResult->intervals_used=$CalculateLengthOfStayUseCaseResult->intervals_used;
    new ResponseJson(200, $InformationOfPaymentsByPlateUseCaseResult);


   
}catch(Exception $e){
    new ResponseJson($e->getCode(),$e->getMessage());
}

