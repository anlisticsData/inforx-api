<?php

use Commons\Uteis;
use Dtos\ModuleDto;
use Dtos\MovementsDto;
use Commons\HttpRequests;
use Commons\ResponseJson;
use Requests\UserRequest;
use Resources\HttpStatus;
use Resources\APPLICATION;
use Services\UserServices;
use UseCases\Settings\GetTypeUseCase;
use Adapters\PdoMysqlConectedModuleAdapter;
use Repositories\Settings\SettingRepository;
use UseCases\Movements\ByUuidMovementUserCase;
use UseCases\Movements\CreateMovementUserCase;
use Repositories\Movements\MovementsRepository;
use UseCases\Settings\UpdateContentTypeUseCase;
use UseCases\Movements\RedeemOpenPlatesOfTheDay;
use UseCases\Movements\CheckIfItIsNotTheSamePlate;

 
require_once __DIR__ . "./../../../core/Settings.php";


 
 


class JSONValidator {
    // Valida e transforma o JSON recebido
    public static function validateAndTransform($jsonString) {
    
        // Tenta decodificar o JSON
        $data = json_decode($jsonString, true);
  
        // Verifica se o JSON é válido
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("JSON inválido: " . json_last_error_msg());
        }
  
        // Verifica a estrutura do JSON
        $requiredKeys = ["host", "user", "pwd", "port", "base"];
        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Exception("Chave obrigatória ausente: $key");
            }
        }
  
        // Retorna os dados validados
        return $data;
    }
  }
  
  
   
  
  try{
  
    $request = new  UserRequest(HttpRequests::requestJSON());
    $ServicesUser =  new UserServices();
    $user = $ServicesUser->auth($request->login, $request->password);
    $data = JSONValidator::validateAndTransform(base64_decode($user->settings,true));
    $modulo =  new ModuleDto();
    $modulo->ip_server=  $data['host'];
    $modulo->user_server=$data['user'];
    $modulo->port_server=$data['port'];
    $modulo->user_db=$data['base'];
    $modulo->passw_db=$data['pwd'];
    $_SERVER['HTTP_USER_AUTHORIZATION']=$user->token;

    $handle =  new PdoMysqlConectedModuleAdapter($modulo);
    $GetTypeUseCase = new GetTypeUseCase(new SettingRepository());
    $CheckIfItIsNotTheSamePlateUseCase = new CheckIfItIsNotTheSamePlate(new MovementsRepository());
    $ByUuidMovementUserCase =  new ByUuidMovementUserCase(new MovementsRepository());
    $CreateMovementUserCase =  new CreateMovementUserCase(new MovementsRepository());
    $UpdateContentTypeUseCase =  new UpdateContentTypeUseCase(new SettingRepository());
    $RedeemOpenPlatesOfTheDay =  new RedeemOpenPlatesOfTheDay(new MovementsRepository());
  
    
    $settingLastPlate = $GetTypeUseCase->execute(APPLICATION::$APP_LAST_ID_PLATE);
    $dayCurrency=date("d");
    $monthCurrency=date("m");
    $yearCurrency=date("Y");
    $cameras =[];
    $camerasCampos =[];
    foreach($data['cams'] as $i =>$value){
        $cameras[]=$value;
        $camerasCampos[]="portatirasensor=?";
    }
    $where = sprintf(" and year(created_at)=%s and month(created_at)=%s and day(created_at)=%s",$yearCurrency,$monthCurrency,$dayCurrency);
    $outPlateUserCase=$handle->query("select *  from movimentoscameras where ".implode(" || ",$camerasCampos).' '.$where.' ORDER by created_at DESC LIMIT 1 ',$cameras);
    foreach ($outPlateUserCase as $plate) {
            if (!$CheckIfItIsNotTheSamePlateUseCase->execute($plate->placa)) {
              $newCar =  new MovementsDto();
              $newCar->park_entry_date = $plate['created_at'];
              $newCar->park_vehicle_plate =  $plate['placa'];
              $newCar->uuid_ref =  $plate['codigo'];
              $newCar->double_vacancy = 0;
              $newCar->branches_id = $user->branche['id'];
              $newCar->user_entry = $user->id;
              $uuid = str_replace("-", "", str_replace(":", "", str_replace(" ", "_", $newCar->park_entry_date)));
              $newCar->uuid_id_plate_direction_create = sprintf("%s_%s_%s_%s", $plate['codigo'], $plate['placa'], $newCar->branches_id, $uuid);
              $resultMovementsData = $ByUuidMovementUserCase->execute($newCar->uuid_id_plate_direction_create);
              if (Uteis::isNullOrEmpty($resultMovementsData->park_id)) {
                  if (
                      !is_null($CreateMovementUserCase->execute($newCar)) && isset($settingLastPlate->content)
                      && (!trim($settingLastPlate->content) || !is_null($settingLastPlate->content))
                  ) {
                      $settingLastPlate->content = $plate['codigo'];
                  }
              }
              $lastPlate =  $plate['codigo'];
              $UpdateContentTypeUseCase->execute($settingLastPlate);
          }
    }
  
    $outResponse =count( $RedeemOpenPlatesOfTheDay->execute());
    new ResponseJson(HttpStatus::$HTTP_CODE_OK, $outResponse);
    
  }catch(Exception $e){
      new ResponseJson($e->getCode(),$e->getMessage());
  }

  