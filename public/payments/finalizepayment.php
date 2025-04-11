<?php

use Commons\Uteis;
use Commons\HttpRequests;
use Commons\ResponseJson;
use Requests\PaymentRequest;
use Middleware\Authorization;
use UseCases\Price\ByPriceUserCase;
use Repositories\Car\CartypeRepository;
use Repositories\Price\PriceRepository;
use UseCases\Price\PriceActiveUserCase;
use UseCases\Agreements\ByAgreementUseCase;
use Repositories\Branches\BranchesRepository;
use Repositories\Payments\PaymentsRepository;
use UseCases\Payments\FinalizePaymentUseCase;
use UseCases\PaymentTypes\AllPaymentsUseCase;
use UseCases\Movements\ByUuidMovementUserCase;
use Repositories\Movements\MovementsRepository;
use Repositories\Agreements\AgreementRepository;
use Repositories\Payments\PaymentTypesRepository;
use UseCases\Payments\CheckMonthlyPaymentUseCase;
use Repositories\MonthyPlate\MonthyPlateRepository;
use UseCases\Payments\CalculateLengthOfStayUseCase;
use UseCases\MonthyPlates\ByUuidMonthPlatesUserCase;
use UseCases\Payments\InformationOfPaymentsByPlateUseCase;

require_once __DIR__ . "./../../core/Settings.php";
try {
    Authorization::Init();
    $request =  new PaymentRequest(HttpRequests::requestJSON());


    $input = [
        "fk_branch_id" => Authorization::getBranchCode(),
        "ip_address" => Uteis::ip(),
        "fk_user_id" => Authorization::getUserCode(),
        "fk_payment_types" => $request->fk_payment_types,
        "fk_pricing_id" => $request->fk_pricing_id,
        "fk_movements_id" => $request->fk_movements_id,
        "fk_agreements_discont" => $request->fk_agreements_discont,
        "uuid_id_plate_direction_create" => $request->uuid_id_plate_direction_create,
        "receipt_by_box" => $request->receipt_by_box,
        "debts"=>null
    ];

   

    $CalculateLengthOfStayUseCase  = new CalculateLengthOfStayUseCase(
        new MovementsRepository(),
        new PriceActiveUserCase(new PriceRepository()),
        new ByUuidMonthPlatesUserCase(new MonthyPlateRepository()),
        new CheckMonthlyPaymentUseCase(new PaymentsRepository()),
        new PriceRepository(),
        new CartypeRepository()
    );

    $CalculateLengthOfStayUseCaseResult = $CalculateLengthOfStayUseCase->execute($request->uuid_id_plate_direction_create, Authorization::getBranchCode());

    $InformationOfPaymentsByPlateUseCase =  new InformationOfPaymentsByPlateUseCase(
        new MovementsRepository(),
        new PriceActiveUserCase(new PriceRepository()),
        new ByUuidMonthPlatesUserCase(new MonthyPlateRepository()),
        new CheckMonthlyPaymentUseCase(new PaymentsRepository())
    );

    $InformationOfPaymentsByPlateUseCaseResult = $InformationOfPaymentsByPlateUseCase->execute($request->uuid_id_plate_direction_create);
    $InformationOfPaymentsByPlateUseCaseResult->movement->permanence=$CalculateLengthOfStayUseCaseResult->time;
    $InformationOfPaymentsByPlateUseCaseResult->total=$CalculateLengthOfStayUseCaseResult->total;
    $InformationOfPaymentsByPlateUseCaseResult->intervals_used=$CalculateLengthOfStayUseCaseResult->intervals_used;
    $input['debts']=$InformationOfPaymentsByPlateUseCaseResult;
    
    $FinalizePaymentUseCase =  new FinalizePaymentUseCase(
        new PaymentsRepository(),
        new AllPaymentsUseCase(new PaymentTypesRepository()),
        new ByPriceUserCase(new PriceRepository()),
        new ByUuidMovementUserCase(new MovementsRepository()),
        new CheckMonthlyPaymentUseCase(new PaymentsRepository()),
        new ByAgreementUseCase(new AgreementRepository(), new BranchesRepository())
    );




    $FinalizePaymentUseCase->execute($input );
    new ResponseJson(201, $input);
} catch (Exception $e) {
    new ResponseJson($e->getCode(), $e->getMessage());
}
