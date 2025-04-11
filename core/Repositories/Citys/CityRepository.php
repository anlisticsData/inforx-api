<?php

namespace Repositories\Citys;

use PDO;
use Exception;
use Models\City;
use Commons\Uteis;
use Commons\Paginator;
use Resources\Strings;
use Resources\HttpStatus;
use Commons\StringBuilder;
use Resources\APPLICATION;
use Interfaces\IConnections;
use Adapters\PdoMysqlAdapter;
use Commons\DataBaseRepository;
use Interfaces\Citys\ICityRepository;

class CityRepository implements ICityRepository{
    private IConnections $repository;

    public function __construct()
    {
         $this->repository =  new DataBaseRepository();
        
    }

    function records()
    {
        try{
            $sql = new StringBuilder();
            $sql->Insert("SELECT count(*) as records  FROM citys;");
           
            $resultData =$this->repository->query($sql->toString(),null,false);
            return $resultData['records']; 
          
        }catch(Exception $e){
            
            
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return 0;
    }

    function recordsPaginator($stateId) {
        try{
            $sql = new StringBuilder();
            $sql->Insert("SELECT count(*) as records  FROM citys where state_id=?;");
            $resultData =$this->repository->query($sql->toString(),array($stateId),false);
            return $resultData['records']; 
          
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return 0;
    }
    
    function All(){
        $citys=[];
        try{
            $sql = new StringBuilder();
            $sql->Insert("SELECT id,name,state_id,created_at,updated_at FROM citys;");
            $resultData =$this->repository->query($sql->toString());
           foreach($resultData as $index=>$row){
                $citys[] =  new City($row);
           }
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return $citys;
    }

    function SearchCitiesByState($stateId){
        $citys=[];
        try{
            $sql = new StringBuilder();
            $sql->Insert("SELECT id,`name`,state_id,created_at,updated_at FROM citys where state_id=?;");
            $resultData = $this->repository->query($sql->toString(),array($stateId));
           foreach($resultData as $index=>$row){
                $citys[] = new City($row);
           }
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return $citys;
    }


    

    function SearchCitiesBy($stateId){
        $citys=[];
        try{
            $sql = new StringBuilder();
            $sql->Insert("SELECT id,`name`,state_id,created_at,updated_at FROM citys where id=?;");
            $resultData = $this->repository->query($sql->toString(),array($stateId));
           foreach($resultData as $index=>$row){
                $citys[] = new City($row);
           }
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return $citys;
    }

    


    function SearchCitiesAll($start=1,$sizeCity,$limit=15){
       
        $citys=[];
        $limitFilter=APPLICATION::$APP_RETURN_EMPTY;
        if(!is_null($start) && !is_null($sizeCity)){
            $limitFilter = sprintf("limit %s,%s",$start,$limit);
        }
       
        try{
            $sql = new StringBuilder();
            $sql->Insert(sprintf("SELECT id,name,state_id,created_at,updated_at FROM citys %s ;",$limitFilter));
           $resultData =$this->repository->query($sql->toString());
           foreach($resultData as $index=>$row){
                $citys[] =  new City($row);
           }
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return $citys;
    }

    




}


