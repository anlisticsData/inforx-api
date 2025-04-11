<?php

use Commons\Uteis;
use Commons\HttpRequests;
use Commons\ResponseJson;
use Services\UserServices;
use Dtos\UserRequeChangeDto;
use Middleware\Authorization;
use Requests\UserRequeChangeRequest;
use Repositories\Users\UserRepository;
use Resources\HttpStatus;
use UseCases\Users\UpdateChangePasswordUseCase;

require_once __DIR__ . "./../../core/Settings.php";
try {
    Authorization::Init();
    $request = new  UserRequeChangeRequest(HttpRequests::requestJSON());
    $changePasswordUserCase =  new UpdateChangePasswordUseCase(new UserRepository(),new UserServices());
    $userPlayload =  Authorization::playload();
    $response =  $changePasswordUserCase->execute($userPlayload,new UserRequeChangeDto($request->toArray()));
    if($request) new ResponseJson(HttpStatus::$HTTP_CODE_OK);
} catch (Exception $e) {
    new ResponseJson($e->getCode(), $e->getMessage());
}
