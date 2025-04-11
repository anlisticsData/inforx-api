<?php

use Dtos\AllDto;
use Models\Setting;
use Commons\HttpRequests;
use Commons\ResponseJson;
use Commons\ResponseText;
use Requests\UserRequest;
use Resources\HttpStatus;
use Resources\APPLICATION;
use Services\UserServices;
use Dtos\PricesIntervalsDto;
use Middleware\Authorization;
use UseCases\GetTypeAllUserCase;
use UseCases\Price\ByPriceUserCase;
use UseCases\Settings\GetTypeUseCase;
use Repositories\Car\CartypeRepository;
use Repositories\Price\PriceRepository;
use UseCases\Settings\CreateTypeUseCase;
use Repositories\Colors\ColorsRepository;
use UseCases\Colors\GetColorsAllUserCase;
use Repositories\Settings\SettingRepository;
use UseCases\Agreements\AllAgreementUseCase;
use UseCases\Cartypes\TypeOfVehiclesUseCase;
use UseCases\Price\AllPriceIntervalUserCase;
use Repositories\Branches\BranchesRepository;
use UseCases\PaymentTypes\AllPaymentsUseCase;
use Repositories\Agreements\AgreementRepository;
use Repositories\Payments\PaymentTypesRepository;
use Repositories\MonthlyPayers\MonthlyPayersRepository;
use UseCases\Monthlypayers\ReserveAPlaceForTheMonthlyMemberUseCase;



 

require_once __DIR__ . "./../../core/Settings.php";
try {

    $request = new  UserRequest(HttpRequests::requestJSON());
    $ServicesUser =  new UserServices();
    $AllPaymentsUseCase =  new AllPaymentsUseCase(new PaymentTypesRepository());
    $GetCartypeUseCase =  new GetTypeAllUserCase(new CartypeRepository());
    $GetColorUseCase =  new GetColorsAllUserCase(new ColorsRepository());
    $GetTypeUseCase =  new GetTypeUseCase(new SettingRepository());
    $CreateTypeUseCase =  new CreateTypeUseCase(new SettingRepository());
    $AllPriceIntervalUserCase =  new AllPriceIntervalUserCase(new PriceRepository());
    $TypeOfVehiclesUseCase =  new TypeOfVehiclesUseCase(new CartypeRepository());
    $allAgreementUseCase =  new AllAgreementUseCase(new AgreementRepository(),new BranchesRepository());
    $CreateTypeUseCase->execute(new Setting(["type"=> sprintf(APPLICATION::$APP_LAST_ID_PLATE_REMOTE, $user->branches_id),"content"=>0]));
    $CreateTypeUseCase->execute(new Setting(["type"=> sprintf(APPLICATION::$APP_TABLE_DEFAULT_GERAL_CARROS, $user->branches_id),"content"=>'']));
    $CreateTypeUseCase->execute(new Setting(["type"=> sprintf(APPLICATION::$APP_TABLE_DEFAULT_GERAL_MOTOS, $user->branches_id),"content"=>'']));
    $CreateTypeUseCase->execute(new Setting(["type"=> sprintf(APPLICATION::$APP_TABLE_DEFAULT_MENSALISTAS, $user->branches_id),"content"=>'']));
    $ReserveAPlaceForTheMonthlyMemberUseCase = new ReserveAPlaceForTheMonthlyMemberUseCase(new MonthlyPayersRepository());
     $user=$ServicesUser->auth($request->login, $request->password);
    new ResponseText(200,$user->token);
} catch (Exception $e) {
    new ResponseText($e->getCode(), $e->getMessage());
}
