<?php

use Commons\Uteis;
use Dtos\CustomerDto;
use Resources\Strings;
use Commons\HttpRequests;
use Commons\ResponseJson;
use Resources\HttpStatus;
use Commons\StringBuilder;
use Resources\APPLICATION;
use Services\CityServices;
use Services\UserServices;
use Requests\CustomerRequest;
use Services\CustomerServices;

require_once __DIR__."./../../core/Settings.php";
try{
    $request =  new CustomerRequest(HttpRequests::requestJSON());
    $customer =  new CustomerDto();
    $ServiceCustomer =  new CustomerServices();
    $ServiceUser =  new UserServices();
    
    $customer->id =  $request->id;
    $customer->description =  $request->description;
    $customer->cnpj =  $request->cnpj;
    $customer->address =  $request->address;
    $customer->state = APPLICATION::$APP_CODE_ACTIVE_STATE;
    $customer->created_at =  $request->created_at;
    $customer->deleted_at =  $request->deleted_at;
    $customer->email =  $request->email;
    $customer->password =  $request->password;
    $customer->group_id =  APPLICATION::$APP_CODE_DEFAULT_CUSTOMERS;

    $ServiceUser->CheckUserExists($customer->email,$customer->password);
    $result = $ServiceCustomer->create($customer);
    
    if(!is_null($result)){
        new ResponseJson(HttpStatus::$HTTP_CODE_CREATED,$result);
    }
    new ResponseJson(HttpStatus::$HTTP_CODE_BAD_REQUEST,Strings::$STR_USER_CREATED_COM_NOT_SUCESS);

}catch(Exception $e){
    new ResponseJson($e->getCode(),$e->getMessage());
}

