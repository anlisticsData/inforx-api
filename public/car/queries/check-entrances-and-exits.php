<?php

use Commons\Uteis;
use Models\Setting;
use Dtos\MovementsDto;
use Commons\HttpRequests;
use Commons\ResponseJson;
use Resources\HttpStatus;
use Resources\APPLICATION;
use Middleware\Authorization;
use Requests\HasNewPlateInput;
use UseCases\Modules\FindOneUserCase;
use UseCases\Settings\GetTypeUseCase;
use UseCases\Settings\CreateTypeUseCase;
use Repositories\Modules\ModuleRepository;
use UseCases\Modules\HasNewPlatesUserCase;
use Adapters\PdoMysqlConectedModuleAdapter;
use Repositories\Settings\SettingRepository;
use UseCases\Movements\ByUuidMovementUserCase;
use UseCases\Movements\CreateMovementUserCase;
use Repositories\Movements\MovementsRepository;
use UseCases\Settings\UpdateContentTypeUseCase;
use UseCases\Movements\RedeemOpenPlatesOfTheDay;
use UseCases\Movements\CheckIfItIsNotTheSamePlate;

require_once __DIR__ . "./../../../core/Settings.php";
require_once __DIR__ . "./../../../mocks/MockModuleRepository.php";
try {

    Authorization::Init();
    $request = new HasNewPlateInput(HttpRequests::Requests());
    $playload = Authorization::playload();
    $GetTypeUseCase = new GetTypeUseCase(new SettingRepository());
    $CreateTypeUseCase = new CreateTypeUseCase(new SettingRepository());
    $CheckIfItIsNotTheSamePlateUseCase = new CheckIfItIsNotTheSamePlate(new MovementsRepository());
    $UpdateContentTypeUseCase =  new UpdateContentTypeUseCase(new SettingRepository());
    $ByUuidMovementUserCase =  new ByUuidMovementUserCase(new MovementsRepository());
    $CreateMovementUserCase =  new CreateMovementUserCase(new MovementsRepository());
    $RedeemOpenPlatesOfTheDay =  new RedeemOpenPlatesOfTheDay(new MovementsRepository());
    $settingLastPlate = $GetTypeUseCase->execute(APPLICATION::$APP_LAST_ID_PLATE);
    if (is_null($settingLastPlate)) {
        $CreateTypeUseCase->execute(new Setting([
            "type" => APPLICATION::$APP_LAST_ID_PLATE,
            "content" => 0
        ]));
    }
    $settingLastPlate = $GetTypeUseCase->execute(APPLICATION::$APP_LAST_ID_PLATE);
    $lastPlate = (is_null($settingLastPlate)) ? 0 : $settingLastPlate->content;
    $FindOneUserCase =  new FindOneUserCase(new ModuleRepository());
    $outPutUserCase = $FindOneUserCase->execute($request->module);
    $ModuleConected = new ModuleRepository(new PdoMysqlConectedModuleAdapter($outPutUserCase));
    $PlateUserCase = new HasNewPlatesUserCase($ModuleConected);
    $outPlateUserCase = $PlateUserCase->execute($lastPlate);
    foreach ($outPlateUserCase as $plate) {
        if (!$CheckIfItIsNotTheSamePlateUseCase->execute($plate->placa)) {
            $newCar =  new MovementsDto();
            $newCar->park_entry_date = $plate->created_at;
            $newCar->park_vehicle_plate =  $plate->placa;
            $newCar->uuid_ref =  $plate->codigo;
            $newCar->double_vacancy = 0;
            $newCar->branches_id = $playload['branch'];
            $newCar->user_entry = $playload['user'];
            $uuid = str_replace("-", "", str_replace(":", "", str_replace(" ", "_", $newCar->park_entry_date)));
            $newCar->uuid_id_plate_direction_create = sprintf("%s_%s_%s_%s", $newCar->uuid_ref, $newCar->park_vehicle_plate, $newCar->branches_id, $uuid);
            $resultMovementsData = $ByUuidMovementUserCase->execute($newCar->uuid_id_plate_direction_create);
            if (Uteis::isNullOrEmpty($resultMovementsData->park_id)) {
                if (
                    !is_null($CreateMovementUserCase->execute($newCar)) && isset($settingLastPlate->content)
                    && (!trim($settingLastPlate->content) || !is_null($settingLastPlate->content))
                ) {
                    $settingLastPlate->content = $plate->codigo;
                }
            }
            $lastPlate =  $plate->codigo;
            $UpdateContentTypeUseCase->execute($settingLastPlate);
        }
    }
    $outResponse = $RedeemOpenPlatesOfTheDay->execute();
    new ResponseJson(HttpStatus::$HTTP_CODE_OK, $outResponse);
} catch (Exception $e) {
    new ResponseJson($e->getCode(), $e->getMessage());
}
