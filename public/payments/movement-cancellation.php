<?php

use Resources\Strings;
use Commons\HttpRequests;
use Commons\ResponseJson;
use Resources\HttpStatus;
use Requests\DeleteRequest;
use Middleware\Authorization;
use UseCases\Movements\CancellationUserCase;
use Repositories\Movements\MovementsRepository;
 
require_once __DIR__ . "./../../core/Settings.php";
try {

    Authorization::Init();
    $request =  new DeleteRequest(HttpRequests::Requests());
    $cancellationUserCase =  new CancellationUserCase(new MovementsRepository());
    $resultCancellation =  $cancellationUserCase->execute($request->id);
    if (!is_null($resultCancellation) && $resultCancellation) {
        new ResponseJson(HttpStatus::$HTTP_CODE_NO_CONTENT);
    } else {
        new ResponseJson(HttpStatus::$HTTP_CODE_NOT_EXIST, [
            Strings::$STR_ERROR_DELETED
        ]);
    }
} catch (Exception $e) {

    new ResponseJson($e->getCode(), $e->getMessage());
}
