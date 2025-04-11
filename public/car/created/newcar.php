<?php

use Commons\Clock;
use Commons\Uteis;
use Dtos\MovementsDto;
use Requests\CarRequest;
use Commons\HttpRequests;
use Commons\ResponseJson;
use Resources\HttpStatus;
use Middleware\Authorization;
use UseCases\Colors\GetByColorUseCase;
use Repositories\Car\CartypeRepository;
use Repositories\Colors\ColorsRepository;
use UseCases\Cartypes\GetByCartypeUseCase;
use UseCases\Cartypes\ByTypeOfVehiclesUseCase;
use Repositories\Movements\MovementsRepository;
use UseCases\Movements\CreateMovementManualUserCase;

 
require_once "../../../core/Settings.php";
require_once __DIR__ . "./../../../core/Settings.php";
try {
    Authorization::Init();
    $request = new CarRequest(HttpRequests::Requests());
    $playload=Authorization::playload();
    $newCar =  new MovementsDto();
    $newCar->park_entry_date=Clock::NowDate();
    $newCar->park_vehicle_plate = strtoupper( $request->plate);
    $newCar->uuid_ref =  0;
    $newCar->double_vacancy =0;
    $newCar->fk_cartype_id=$request->fk_typecar;
    $newCar->fk_color_id = $request->fk_color;
    $newCar->fk_type_of_vehicle =  $request->fk_type_of_vehicle;
    $newCar->type_print =  $request->type_print;
    $newCar->prisma =  $request->prisma;
    $newCar->branches_id=Authorization::getBranchCode();
    $newCar->user_entry = Authorization::getUserCode();
    $uuid =str_replace("-","",str_replace(":","",str_replace(" ","_",$newCar->park_entry_date)));
    $newCar->uuid_id_plate_direction_create=sprintf("%s_%s_%s_%s",$newCar->uuid_ref,$newCar->park_vehicle_plate,$newCar->branches_id,$uuid);
    $CreateMovementManualUserCase = new CreateMovementManualUserCase(
        new MovementsRepository(),
        new GetByColorUseCase(new ColorsRepository()),
        new GetByCartypeUseCase(new CartypeRepository()),
        new ByTypeOfVehiclesUseCase(new CartypeRepository())

    );
    new ResponseJson(HttpStatus::$HTTP_CODE_CREATED, $CreateMovementManualUserCase->execute($newCar));
} catch (Exception $e) {
    new ResponseJson($e->getCode(), $e->getMessage());
}
