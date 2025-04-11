<?php
namespace Middleware;



use Exception;
use Commons\Uteis;
use Resources\Strings;
use Resources\HttpStatus;
use Services\UserServices;

class AuthorizationResources{
    public static function Init(){
       try{
            $headerType="Authorization";
            $headerTypeUSer="User-Authorization";
         

            $headerTypeResource="Resource";
            $headers=[$headerType,$headerTypeResource,$headerTypeUSer];
            $headersHttpRequest=[];
            foreach (getallheaders() as $header => $value) {
                if(in_array($header,$headers)){
                    $headersHttpRequest[$header]=trim($value);
                }
            }

            foreach ($_SERVER as $key => $value) {
                if (strpos($key, 'HTTP_') === 0) {
                    // Exibir os cabeçalhos
                    if(in_array($header,$headers)){
                        $header_ = str_replace('HTTP_', '', $key);
                        $header_ = str_replace('_', ' ', $header_);
                        $header_ = ucwords(strtolower($header_));
                        $headersHttpRequest[$headerTypeUSer]=trim($value);
                    }
                }
            }



            if(!isset($headersHttpRequest[$headerType]) && !isset($headersHttpRequest[$headerTypeUSer]) || !isset($headersHttpRequest[$headerTypeResource])){
                throw new Exception(Strings::$STR_USER_TOKEN_INVALIDE,HttpStatus::$HTTP_CODE_UNAUTHORIZED);
            }

            if(isset($headersHttpRequest[$headerType])){
                $headerType__=$headerType;
            }
            if(isset($headersHttpRequest[$headerTypeUSer])){
                $headerType__=$headerTypeUSer;
            }


            $SeviceUser =  new UserServices();
            $SeviceUser->ValidateUserAccessResource(
                $headersHttpRequest[$headerType__],
                Uteis::transformScriptFileToAccess(),
                $headersHttpRequest[$headerTypeResource]
            );
       }catch(Exception $e){
            throw  new Exception($e->getMessage(),$e->getCode());
       }
    }

}

 