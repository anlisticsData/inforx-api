<?php
use Commons\HttpRequests;
use Commons\ResponseJson;

use Resources\HttpStatus;
use Services\UserServices;
use Requests\UserUpdateRequest;
require_once __DIR__."./../../core/Settings.php";
try{
    $request =new  UserUpdateRequest(HttpRequests::requestJSON());
    $ServicesUser =  new UserServices();
    $user=$ServicesUser->updateProfile(
        $request->code_user,
        $request->name,
        $request->password, 
        $request->is_update_password 
    );
    
    new ResponseJson(HttpStatus::$HTTP_CODE_OK,$user);
}catch(Exception $e){
    new ResponseJson($e->getCode(),$e->getMessage());
}