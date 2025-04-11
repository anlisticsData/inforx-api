<?php

namespace Repositories\RecoveryUser;

use AnalisticsData\ClockSystem;
use Commons\Clock;
use Interfaces\IConnections;
use Commons\DataBaseRepository;
use Commons\StringBuilder;
use Commons\Uteis;
use Exception;
use Resources\HttpStatus;
use Interfaces\RecorevyUser\IRecoveryUser;
use Resources\APPLICATION;
use Dtos\RecoveryDto;

class RecoveryRepository implements IRecoveryUser{
    private IConnections $repository;

    public function __construct()
    {
        $this->repository =  new DataBaseRepository();
    }
    

    function createdRecovery($userCode,$codeGenetated){
        try{
            $limitTime=Clock::NowDate();
            $nowDate=Clock::NowDate();
            $sql = new StringBuilder();
            $sql->Insert("INSERT INTO recovery_user(`code`,user_code,created_at,validate_at) ");
            $sql->Insert(" VALUES(?,?,?,?)");
            $data=[$codeGenetated,$userCode,$nowDate,$limitTime];
            $resultData = $this->repository->executeAutoIncrement($sql->toString(),$data);
            return $resultData;
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
        
    }
    function codeValidate($codeGenetated,$codeUser){
        try{
            $sql = new StringBuilder();
            $sql->Insert("SELECT `id`, `user_code`, `created_at`,`validate_at` FROM recovery_user ");
            $sql->Insert(" where code=? and user_code=?");
            $resultData = $this->repository->query($sql->toString(),array($codeGenetated,$codeUser),false);
            if(!is_null($resultData)){
                return new RecoveryDto($resultData);
            }
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;

    }

    function by($code){
          try{
            $sql = new StringBuilder();
            $sql->Insert("SELECT `id`, `user_code`, `created_at`,`validate_at` FROM users ");
            $sql->Insert(" where id=?");
            $resultData = $this->repository->query($sql->toString(),array($code),false);
            if(!is_null($resultData)){
                return new RecoveryDto($resultData);
            }
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;

    }

    function delete($code){
        try{
            $sql = new StringBuilder();
            $sql->Insert("DELETE  FROM recovery_user where id=?");
            if($this->repository->query($sql->toString(),array($code),false)) return true;
            return false;
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;

    }





 

}