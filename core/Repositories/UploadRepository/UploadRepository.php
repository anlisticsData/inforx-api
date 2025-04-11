<?php

namespace Repositories\UploadRepository;
use Dtos\UploadDto;
use Commons\DataBaseRepository;
use Commons\StringBuilder;
use Exception;
use Resources\HttpStatus;
use Commons\Uteis;
use Interfaces\IConnections;
use Interfaces\UploadRepository\IUploadRepository;

class UploadRepository implements IUploadRepository{
    private IConnections $repository;
    public function __construct()
    {
        $this->repository =  new DataBaseRepository();
    }

    function by($uploadId){
        try{
            $sql = new StringBuilder();
            $sql->Insert("SELECT id,uuid,ext,`description`,`path`,created_at  FROM uploads where id=?");
            $parameters = [$uploadId];
            $resultData =$this->repository->query($sql->toString(),$parameters,false);
            return  new UploadDto($resultData); 
          
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }

    function records()
    {
        try{
            $sql = new StringBuilder();
            $sql->Insert("SELECT count(*) as records  FROM uploads;");
            $resultData =$this->repository->query($sql->toString(),null,false);
            return $resultData['records']; 
          
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return 0;
    }
    
    function created(UploadDto $file){
        try{
            $sql = new StringBuilder();
            $sql->Insert("INSERT INTO uploads(uuid,`description`,`path`,`ext`,created_at) ");
            $sql->Insert(" VALUES(?,?,?,?,now())");
            $data=[Uteis::Uuid(),$file->description,$file->path,$file->ext];
            $resultData = $this->repository->executeAutoIncrement($sql->toString(),$data);
            return $resultData;
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }


}


