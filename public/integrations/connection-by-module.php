<?php

use Commons\Uteis;
use Dtos\ModuleDto;
use Resources\Strings;
use Commons\HttpRequests;
use Commons\ResponseJson;
use Resources\HttpStatus;
use Dtos\JSONValidatorDto;
use Services\UserServices;
use Middleware\Authorization;
use Requests\HasNewPlateInput;
use Services\BranchesServices;
use Repositories\Car\CartypeRepository;
use Adapters\PdoMysqlConectedModuleAdapter;
use UseCases\Cartypes\TypeOfVehiclesUseCase;
use UseCases\Movements\CreateMovementUserCase;
use Repositories\Movements\MovementsRepository;
use UseCases\MovementCameras\MaxRemoteRefUseCase;
use UseCases\Movements\ByUuidMovementPlateUserCase;
use UseCases\Modules\SearchCurrentLicensePlatesUserCase;
use UseCases\MovementCameras\RecordMovementsLocalUseCase;
use Repositories\MovementCameras\MovimentCamerasRepository;
use Repositories\Payments\PaymentsRepository;
use UseCases\MovementCameras\ProcessMovementRemoteInLocalUserCase;
require_once __DIR__."./../../core/Settings.php";
try {
    Authorization::Init();
    $request = HttpRequests::Init();
    $request = new HasNewPlateInput([
        "module" => $request['module'],
        "customer" => Authorization::CustomerId(),
        "branch" => Authorization::getBranchCode()
    ]);
    $branchesServices =  new BranchesServices();
    $ServicesUser =  new UserServices();
    $codeIser = Authorization::getUserCode();
    $user = $ServicesUser->by($codeIser);
    $branchInformations = $branchesServices->services($user->branches_id);
    if (count($branchInformations) == 0) {
        throw new Exception(Strings::$APP_PRINCES_TYPES_ERROR_NOT_EXISTS, HttpStatus::$HTTP_CODE_BAD_REQUEST);
    }
    $TypeOfVehiclesUseCase = new TypeOfVehiclesUseCase(new CartypeRepository());
    $vehicles = $TypeOfVehiclesUseCase->execute();
    $ByUuidMovementUserCase =  new ByUuidMovementPlateUserCase(new MovementsRepository());
    $CreateMovementUserCase =  new CreateMovementUserCase(new MovementsRepository());
    $branchInformationsParser = JSONValidatorDto::validateAndTransform(base64_decode($branchInformations['results'][0]['settings'], true));
    $MaxRemoteRefUseCase = new MaxRemoteRefUseCase(new MovimentCamerasRepository());
    $resultMaxRemoteCamera =   $MaxRemoteRefUseCase->execute();
    $modulo =  new ModuleDto(
        [
            "ip_server" => $branchInformationsParser['host'],
            "user_server" => $branchInformationsParser['user'],
            "port_server" => $branchInformationsParser['port'],
            "user_db" => $branchInformationsParser['base'],
            "passw_db" => $branchInformationsParser['pwd']

        ]
    );
    $SearchCurrentLicensePlatesUserCase =  new SearchCurrentLicensePlatesUserCase(new PdoMysqlConectedModuleAdapter($modulo));
    $movementsRemote = $SearchCurrentLicensePlatesUserCase->execute($resultMaxRemoteCamera, $branchInformationsParser);
    $RecordMovementsLocalUseCase =  new RecordMovementsLocalUseCase(new MovimentCamerasRepository());
    $RecordMovementsLocalUseCase->execute($movementsRemote, $user->branches_id);
    $ProcessMovementRemoteInLocalUserCase =  new ProcessMovementRemoteInLocalUserCase(new MovimentCamerasRepository(), new MovementsRepository(), new PaymentsRepository);
    $ProcessMovementRemoteInLocalUserCase->execute($user, $vehicles, $ByUuidMovementUserCase, $CreateMovementUserCase);
    new ResponseJson(HttpStatus::$HTTP_CODE_NO_CONTENT);
} catch (Exception $e) {
    new ResponseJson($e->getCode(), $e->getMessage());
}
