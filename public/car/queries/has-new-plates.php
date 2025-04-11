<?php

use Commons\Uteis;
use Dtos\ModuleDto;
use Dtos\MovementsDto;
use Resources\Strings;
use Commons\HttpRequests;
use Commons\ResponseJson;
use Resources\HttpStatus;
use Dtos\JSONValidatorDto;
use Resources\APPLICATION;
use Services\UserServices;
use Middleware\Authorization;
use Requests\HasNewPlateInput;
use Services\BranchesServices;
use UseCases\Settings\GetTypeUseCase;
use Repositories\Car\CartypeRepository;
use Repositories\Price\PriceRepository;
use UseCases\Price\PriceActiveUserCase;
use Adapters\PdoMysqlConectedModuleAdapter;
use Repositories\Settings\SettingRepository;
use UseCases\Cartypes\TypeOfVehiclesUseCase;
use Repositories\Payments\PaymentsRepository;
use UseCases\Movements\ByUuidMovementUserCase;
use UseCases\Movements\CreateMovementUserCase;
use Repositories\Movements\MovementsRepository;
use UseCases\Settings\UpdateContentTypeUseCase;
use UseCases\Movements\RedeemOpenPlatesOfTheDay;
use UseCases\Payments\CheckMonthlyPaymentUseCase;
use UseCases\Movements\CheckIfItIsNotTheSamePlate;
use Repositories\MonthyPlate\MonthyPlateRepository;
use UseCases\Payments\CalculateLengthOfStayUseCase;
use UseCases\MonthyPlates\ByUuidMonthPlatesUserCase;


require_once "../../../core/Settings.php";
require_once __DIR__ . "./../../../core/Settings.php";
require_once __DIR__ . "./../../../mocks/MockModuleRepository.php";
try {
    Authorization::Init();
    $CalculateLengthOfStayUseCase  = new CalculateLengthOfStayUseCase(
        new MovementsRepository(),
        new PriceActiveUserCase(new PriceRepository()),
        new ByUuidMonthPlatesUserCase(new MonthyPlateRepository()),
        new CheckMonthlyPaymentUseCase(new PaymentsRepository()),
        new PriceRepository(),
        new CartypeRepository()
    );
    $RedeemOpenPlatesOfTheDay =  new RedeemOpenPlatesOfTheDay(new MovementsRepository());
    $outResponse = $RedeemOpenPlatesOfTheDay->execute();
    foreach ($outResponse as $index => $row) {
        $calcutate = $CalculateLengthOfStayUseCaseResult = $CalculateLengthOfStayUseCase->execute($row->uuid_id_plate_direction_create, Authorization::getBranchCode());
        $outResponse[$index]->calculate = $calcutate->total;
        $outResponse[$index]->interval = $calcutate->intervals_used;
    }
    new ResponseJson(HttpStatus::$HTTP_CODE_OK, $outResponse);
} catch (Exception $e) {
    new ResponseJson($e->getCode(), $e->getMessage());
}
