<?php

use Commons\ResponseJson;
use Resources\HttpStatus;
use Middleware\Authorization;
use Repositories\Users\UserRepository;
use UseCases\Core\HasPermissionUseCase;




require_once "../core/Settings.php";
require_once __DIR__ . "./../core/Settings.php";
try {
    Authorization::Init();

    $s=new UserRepository();
    $listPermissions=$s->permissions(Authorization::getUserGroup());
    $userpermissions=['public#car#queries#search#for#open#cars#by#license#plate#or#codes'];
    $hasPermissionUseCase =  new HasPermissionUseCase();
    new ResponseJson(HttpStatus::$HTTP_CODE_OK, $hasPermissionUseCase->execute($userpermissions, $listPermissions));
 
 
} catch (Exception $e) {
    new ResponseJson($e->getCode(), $e->getMessage());
}
