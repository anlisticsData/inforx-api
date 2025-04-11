<?php

use Adapters\PhpModuleAdapter;
use Commons\HttpRequests;
use Commons\ResponseJson;
use Commons\Uteis;
use Dtos\EmailSend;
use Models\User;
use Requests\UserRecoverPasswordRequest;
use Resources\Strings;
use Services\SendToSmtp;
use Services\UserServices;

require_once __DIR__."./../../core/Settings.php";
try{
    $request =  new UserRecoverPasswordRequest(HttpRequests::Requests());
    $UserServices = new UserServices();
    if($UserServices->recoverToPassword($request->name,$request->email) > 0){
        new ResponseJson(200,Strings::$STR_EMAIL_CODE_SEND);
    }
    new ResponseJson(400,Strings::$STR_EMAIL_CODE_NOT_SEND);
}catch(Exception $e){
    new ResponseJson($e->getCode(),$e->getMessage());
}