<?php

use Commons\Uteis;
use Commons\HttpRequests;
use Commons\ResponseJson;
use Middleware\Authorization;
use Requests\HasNewPlateInput;
use UseCases\Modules\FindOneUserCase;
use Repositories\Modules\ModuleRepository;
use UseCases\Modules\HasNewPlatesUserCase;
use Adapters\PdoMysqlConectedModuleAdapter;

require_once __DIR__."./../../core/Settings.php";

try{
    $request =new HasNewPlateInput(HttpRequests::Requests());
    $FindOneUserCase =  new FindOneUserCase(new ModuleRepository());
    $outPutUserCase = $FindOneUserCase->execute($request->module);
    $ModuleConected=new ModuleRepository(new PdoMysqlConectedModuleAdapter($outPutUserCase));
    $PlateUserCase = new HasNewPlatesUserCase($ModuleConected);
    $outPutUserCase = $PlateUserCase->execute();
    new ResponseJson(200,$outPutUserCase);
}catch(Exception $e){
    Uteis::dd($e);
    new ResponseJson($e->getCode(),$e->getMessage());
}