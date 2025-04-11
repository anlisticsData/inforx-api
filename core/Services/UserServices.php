<?php

namespace Services;

use DateTime;
use Exception;
use Commons\Jwt;
use Models\User;

use Dtos\UserDto;
use Commons\Clock;
use Commons\Uteis;
use Dtos\EmailSend;
use Resources\Strings;
use Services\SendToSmtp;
use Commons\PasswordHash;
use Interfaces\IServices;
use Resources\HttpStatus;
use Commons\StringBuilder;
use Resources\APPLICATION;
use Adapters\PhpModuleAdapter;
use Repositories\Users\UserRepository;
use Repositories\Branches\BranchesRepository;
use Repositories\Customers\CustomerRepository;
use Repositories\RecoveryUser\RecoveryRepository;
use Repositories\UploadRepository\UploadRepository;
 

class UserServices implements IServices{
    private $UserRepository;
    private $RecoveryUserRepository;
    private $UploadRepository;
    private BranchesRepository $BranchesRepository;
    private CustomerRepository $customerRepository;

    function __construct()
    {
        $this->UserRepository = new UserRepository();
        $this->UploadRepository =  new UploadRepository();
        $this->RecoveryUserRepository =  new RecoveryRepository();
        $this->BranchesRepository= new BranchesRepository();
        $this->customerRepository=new CustomerRepository();  


    }




    function resetPassword($code,$email,$newpassword){
        $userData  =  $this->UserRepository->byEmail($email);
        $codeIsValid  =  $this->RecoveryUserRepository->codeValidate($code, $userData->id);
        if(is_null($codeIsValid) || Uteis::isNullOrEmpty($codeIsValid->created_at)){
            throw new Exception(Strings::$STR_CODE_RECOVER_INVALID_NOT_EXIST,HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }
        $dateStart = new \DateTime($codeIsValid->created_at);
        $dateNow   = new \DateTime(date('Y-m-d H:i:s'));
        $diff = $dateStart->diff($dateNow);
        if($diff->i > APPLICATION::$APP_CODE_TIMER_EXPIRED){
            $this->RecoveryUserRepository->delete($codeIsValid->id);
            throw new Exception(Strings::$STR_CODE_RECOVER_INVALID,HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }
        $password = PasswordHash::Create($newpassword);
        if($this->UserRepository->userUpdatePassword($userData->id,$password)){
            $this->RecoveryUserRepository->delete($codeIsValid->id);
            throw new Exception(Strings::$STR_PASSWORD_UPDATE_OK,HttpStatus::$HTTP_CODE_OK);
        }
        throw new Exception(Strings::$STR_PASSWORD_UPDATE_NOT_OK,HttpStatus::$HTTP_CODE_BAD_REQUEST);
    }
    

    function  recoverToPassword($userName,$userEmail){
        $userData  =  $this->UserRepository->validateUserNameEmail($userName,$userEmail);
        if(is_null($userData)){
            throw new Exception(Strings::$STR_USER_NAME_OR_EMAIL_INVALID,HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }
        $code = PhpModuleAdapter::Implode(Uteis::generationCode(),"");
        $email =  new EmailSend("Recuperação de Senha",
                                "Teste enviado através do PHP Mailer  SMTPLW",
                                sprintf("<b>Code:</b><h1>%s</h1>",$code)
                            );
        $email->addAdrress($userName,$userEmail);
        $SMTP =  new SendToSmtp($email);
        $SMTP->send();
        return $this->RecoveryUserRepository->createdRecovery($userData->id,$code);

    }

    function menusBy($code){
        try{
            $userData = $this->UserRepository->menusBy($code);
            if(is_null($userData)){
                throw new Exception(Strings::$STR_USER_CREATE_INVALID,HttpStatus::$HTTP_CODE_BAD_REQUEST);
            }
            return  $userData;
          
        }catch(Exception $e){
            throw new Exception($e->getMessage(),$e->getCode());
        }
    }


    function by($code){
        try{
            $userData = $this->UserRepository->By($code);
            if(is_null($userData)){
                throw new Exception(Strings::$STR_USER_CREATE_INVALID,HttpStatus::$HTTP_CODE_BAD_REQUEST);
            }
            return  $userData;
          
        }catch(Exception $e){
            throw new Exception($e->getMessage(),$e->getCode());
        }
    }


    function CheckUserExists($login,$password){
        try{
            $userData = $this->UserRepository->auth($login,$password);
            if(!is_null($userData)){
                throw new Exception(Strings::$STR_USER_CREATE_INVALID,HttpStatus::$HTTP_CODE_BAD_REQUEST);
            }
          
        }catch(Exception $e){
            throw new Exception($e->getMessage(),$e->getCode());
        }
    }

    function update(User $user){
        try{
            $user->password = PasswordHash::Update($user->password);
            $user->state = APPLICATION::$APP_CODE_ACTIVE_STATE;
            return $this->UserRepository->created($user);
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }

    function created(User $user){
        try{
            $user->password = PasswordHash::Create($user->password);
            $user->state = APPLICATION::$APP_CODE_ACTIVE_STATE;
            return $this->UserRepository->created($user);
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }
    function updateAvatar($codeUser,$codeUpload){
        try{
            $this->UserRepository->updateAvatar($codeUser,$codeUpload);
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
    }
    function updateProfile($codeUser,$fullName,$password,$isUisUpdatePassword){
        try{
            $isUpdateProfile=false;
            $isUpdateProfile=$this->UserRepository->updateFullName($codeUser,$fullName);
            if($isUisUpdatePassword==true){
                $updatePassword = $this->UserRepository->updatePassword($codeUser,PasswordHash::Create($password));
                $isUpdateProfile=$isUpdateProfile || $updatePassword;   
            }
            return $isUpdateProfile;
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return false;
    }


    
    function auth($login,$password){
    

        try{
            $userData = $this->UserRepository->auth($login,$password);
            if(is_null($userData) || !PasswordHash::Verify($password,$userData->password)){
                throw new Exception(Strings::$STR_USER_INVALIDE,HttpStatus::$HTTP_CODE_UNAUTHORIZED);
            }


            $branche = $this->BranchesRepository->SearchBy($userData->branches_id);
            $payload=array(
                "uuid" => uniqid(),
                "email" => $userData->email,
                "user"  => $userData->id,
                "group" => $userData->groups_id,
                "customer" => $userData->customer_id,
                "branch" =>$userData->branches_id,
                "ip"    => sha1($_SERVER['SERVER_ADDR'])
            );
            $jwt = Jwt::encode($payload,$_SERVER["TOKEN_UUID_INSTALL"]);
            $user=new UserDto();
            $user->id=$userData->id;
            $user->name=$userData->name;
            $user->email=$userData->email;
            $user->groups_id=$userData->groups_id;
            $user->avatar_id=$userData->avatar_id;
            $user->state=$userData->state;
            $user->created_at=$userData->created_at;
            $user->updated_at=$userData->updated_at;
            $user->token=$jwt;
            $user->customer_id=$userData->customer_id;
            $user->menus =$this->UserRepository->menusBy($userData->groups_id);
            $user->group =$this->UserRepository->GroupByUser($userData->groups_id);
            $user->permissions =$this->UserRepository->UserAccess($userData->groups_id);
            $user->branches_id=$userData->branches_id;
            $user->branche=(count($branche)>0) ? $branche[0]:null;
            $user->settings=$userData->settings;
            $user->customer_info =  $this->customerRepository->by($userData->customer_id);
            
            $this->UserRepository->UserTokenRegister($userData->id,$jwt,$_SERVER["EXPIRED_SESSION"]);
            if(!is_null($user->avatar_id)){
                $user->avatar = $this->UploadRepository->by($user->avatar_id);
            }
            return $user;
        }catch(Exception $e){
            throw new Exception($e->getMessage(),$e->getCode());
        }
    }
    function ValidateUserAccess($token,$resource){

        $token = $this->clearToken($token);
        $userTokenData =  $this->UserRepository->UserByToken($token);
        if(is_null($userTokenData)){
            throw new Exception(Strings::$STR_USER_TOKEN_INVALIDE,HttpStatus::$HTTP_CODE_UNAUTHORIZED);
        }
        $playload=Jwt::decode($token,$_SERVER["TOKEN_UUID_INSTALL"]);
        $datatime1 = new DateTime(date("Y-m-d H:m:i"));
        $datatime2 = new DateTime($userTokenData->updated_at);
        $diff = $datatime1->diff($datatime2);
        $seconds =$diff->s;

        if($seconds > $userTokenData->expired_time){
            $this->UserRepository->UserInvalidToken($token);
            throw new Exception(Strings::$STR_USER_TOKEN_EXPIRED,HttpStatus::$HTTP_CODE_UNAUTHORIZED);
        }
        $this->UserRepository->UserUpdateToken($token);
        $access =   $this->UserRepository->UserAccess($playload["group"]);




        foreach($access as $index => $permission){
            if($permission->router=="*"){
               return true;
            }
            if($permission->router==$resource || strpos($resource,$permission->router) > 0){
                return true;
            }
        }
        throw new Exception(Strings::$STR_USER_RESOURCE_NOT_AUTHORIZATION,HttpStatus::$HTTP_CODE_UNAUTHORIZED);
    }


    function ValidateUserAccessResource($token,$resource,$routerApplication){




        $token = $this->clearToken($token);
        $userTokenData =  $this->UserRepository->UserByToken($token);
        if(is_null($userTokenData)){
            throw new Exception(Strings::$STR_USER_TOKEN_INVALIDE,HttpStatus::$HTTP_CODE_UNAUTHORIZED);
        }
        $playload=Jwt::decode($token,$_SERVER["TOKEN_UUID_INSTALL"]);
        $datatime1 = new DateTime(date("Y-m-d H:m:i"));
        $datatime2 = new DateTime($userTokenData->updated_at);
        $diff = $datatime1->diff($datatime2);
        $seconds =$diff->s;
        if($seconds > $userTokenData->expired_time){
            $this->UserRepository->UserInvalidToken($token);
            throw new Exception(Strings::$STR_USER_TOKEN_EXPIRED,HttpStatus::$HTTP_CODE_UNAUTHORIZED);
        }
        $this->UserRepository->UserUpdateToken($token);
        $access =   $this->UserRepository->UserAccess($playload["group"]);

        if($this->authorizedAccess($access,[$routerApplication])){
            throw new Exception("",HttpStatus::$HTTP_CODE_OK);
        }
        throw new Exception(Strings::$STR_USER_RESOURCE_NOT_AUTHORIZATION,HttpStatus::$HTTP_CODE_UNAUTHORIZED);
    }


    private function authorizedAccess($access=[],$acessos=[]){




        $routerValidate=0;
        foreach($access as $index => $permission){
            if($permission->router=="*"){
               return true;
            }
            foreach($acessos as $key => $row){
                if($permission->router == $row){
                    $routerValidate++;
                    
                }
            }
        }
        return (count($acessos)==$routerValidate) ? true : false;

    }



    private function clearToken($tokenValue){
        return  preg_replace('/^\s*Bearer\s+/i', '', $tokenValue);  
    }
   

   
}