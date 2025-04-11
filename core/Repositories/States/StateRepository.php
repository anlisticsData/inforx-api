<?php

namespace Repositories\States;

use Adapters\PdoMysqlAdapter;
use PDO;
use Exception;
use Commons\StringBuilder;
use Interfaces\IConnections;
use Commons\DataBaseRepository;
use Interfaces\States\IStateRepository;
use Models\State;
use Resources\HttpStatus;

class StateRepository implements IStateRepository{
    private IConnections $repository;

    public function __construct()
    {
        $this->repository =  new DataBaseRepository();
    }
    function records()
    {
        try{
            $sql = new StringBuilder();
            $sql->Insert("SELECT count(*) as records  FROM states;");
            $resultData =$this->repository->query($sql->toString(),null,false);
            return $resultData['records']; 
          
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return 0;
    }
    
    function All(){
        $states=[];
        try{
            $sql = new StringBuilder();
            $sql->Insert("SELECT id,name,uf,created_at,updated_at FROM states;");
         
            $resultData = $this->repository->query($sql->toString());
           foreach($resultData as $index=>$row){
                $states[] =  new State($row);
           }
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return $states;
    }



    function By($stateId){
        $states=[];
        try{
            $sql = new StringBuilder();
            $sql->Insert("SELECT id,name,uf,created_at,updated_at FROM states where id=?;");
            $resultData = $this->repository->query($sql->toString(),array($stateId));
           foreach($resultData as $index=>$row){
                $states[] =  new State($row);
           }
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return $states;
    }




}


