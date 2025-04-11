<?php

use Dtos\AllDto;
use Models\Setting;
use Commons\HttpRequests;
use Commons\ResponseJson;
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
    $user = $ServicesUser->auth($request->login, $request->password);
    $ByPriceUserCase = new ByPriceUserCase(new PriceRepository());
    $prices = $ByPriceUserCase->execute($user->branches_id);
    $outputCartTypes=$GetCartypeUseCase ->execute();
    $outputColors=$GetColorUseCase ->execute();
    $vacacionesUsed=$ReserveAPlaceForTheMonthlyMemberUseCase->execute($user->customer_id,$user->branches_id);
    $user->branche['available_vacancies']=$user->branche['available_vacancies'] - $vacacionesUsed;
    $settingsTypes=$GetTypeUseCase->execute(sprintf("APP_TABLE_DEFAULT_GERAL_CARROS_%s",$user->branches_id));
    $settingsCarsData=[];
    if(!is_null($settingsTypes)){
        $settingsCarsData=$AllPriceIntervalUserCase->execute(new PricesIntervalsDto(["fk_princes_id"=>$settingsTypes->content,
        "fk_branch_id"=>$user->branches_id]));
    }
    $settingsCars=[
        "type"=>$settingsTypes,
        "prices_intervals"=>$settingsCarsData
    ];


    $settingsTypes=$GetTypeUseCase->execute(sprintf("APP_TABLE_DEFAULT_GERAL_MOTOS_%s",$user->branches_id));
    $settingsMotorcyclesData=[];
    if(!is_null($settingsTypes)){
        $settingsMotorcyclesData=$AllPriceIntervalUserCase->execute(new PricesIntervalsDto(["fk_princes_id"=>$settingsTypes->content,
        "fk_branch_id"=>$user->branches_id]));
    }
    $settingsMotorcycles=[
        "type"=>$settingsTypes,
        "prices_intervals"=>$settingsMotorcyclesData
    ];
    $settingsTypes=$GetTypeUseCase->execute(sprintf("APP_TABLE_DEFAULT_MENSALISTAS_%s",$user->branches_id));
    $settingsMonmentPlayerData=[];
    if(!is_null($settingsTypes)){
        $settingsMonmentPlayerData=$AllPriceIntervalUserCase->execute(new PricesIntervalsDto(["fk_princes_id"=>$settingsTypes->content,
        "fk_branch_id"=>$user->branches_id]));
    }
    $settingsMonmentPlayers=[
        "type"=>$settingsTypes,
        "prices_intervals"=>$settingsMonmentPlayerData
    ];

    $allAgreementResults=$allAgreementUseCase->execute($user->branches_id,new AllDto(),true);


    new ResponseJson(HttpStatus::$HTTP_CODE_OK, $user, [
        "settings" => [
            "price_default" => APPLICATION::$APP_DEFAULT_WITHOUT_REGISTRATION,
            "price" => $prices,
            "payment_types" =>$AllPaymentsUseCase->execute($user->branches_id),
            "cartypes" => $outputCartTypes,
            "colors" => $outputColors,
            "setting_pix"=>$GetTypeUseCase->execute(sprintf("APP_SETTING_PIX_%s",$user->branches_id)),
            "setting_general_cars"=>$settingsCars,
            "setting_general_motorcycles"=>$settingsMotorcycles,
            "setting_general_monthlypayers"=>$settingsMonmentPlayers,
            "type_of_vehicle" =>$TypeOfVehiclesUseCase->execute(),
            "agreements"=>$allAgreementResults
        ]
    ]);
} catch (Exception $e) {
    new ResponseJson($e->getCode(), $e->getMessage());
}
