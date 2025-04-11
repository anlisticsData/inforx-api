<?php 

use Commons\ResponseJson;
use Resources\Strings;
use Commons\HttpRequests;
use Commons\Uteis;
use Requests\UserRecoverRequest;
use Services\UserServices;

require_once __DIR__."./../../core/Settings.php";
try{
    $request =  new UserRecoverRequest(HttpRequests::Requests());
    $UserServices = new UserServices();
    if($UserServices->resetPassword($request->code,$request->email,$request->newpassword) > 0){
        new ResponseJson(200,Strings::$STR_EMAIL_CODE_SEND);
    }
    new ResponseJson(400,Strings::$STR_EMAIL_CODE_NOT_SEND);
}catch(Exception $e){
    new ResponseJson($e->getCode(),$e->getMessage());
}