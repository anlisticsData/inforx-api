<?php

namespace Repositories\Box;

use Exception;
use Models\Box;
use Commons\Uteis;
use Resources\HttpStatus;
use Commons\StringBuilder;
use Interfaces\IConnections;
use Commons\DataBaseRepository;
use Interfaces\Box\IBoxRepository;
 



class BoxRepository implements IBoxRepository {
    private IConnections $repository;
    public function __construct() {
        $this->repository = new DataBaseRepository();
    }

    function records() {
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT count(*) as records FROM `box` where deleted_at is null;");
            $resultData = $this->repository->query($sql->toString(), null, false);
            return $resultData['records'];
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        
        return 0;
    }

    function by($date,$branchCode){
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT * FROM `box` where created_at like ?  and branches_id=?  and closure_date is  null and deleted_at is null");
            $resultData = $this->repository->query($sql->toString(),['%'.$date.'%',$branchCode]);
            if(!is_null($resultData) && count($resultData) > 0){
                    return new Box($resultData[0]);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return  null;
    }

    function isOpenBox($date,$branchCode){
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT * FROM `box` where created_at like ?  and branches_id=? and closure_date is  null and deleted_at is null");
            $resultData = $this->repository->query($sql->toString(),['%'.$date.'%',$branchCode]);
            if(!is_null($resultData) && count($resultData) > 0){
                    return true;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return  false;
    }

    function isCloseBox($date, $branchCode){
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT * FROM `box` where created_at like ?   and branches_id=?  and closure_date is not  null and deleted_at is null");
            $resultData = $this->repository->query($sql->toString(),['%'.$date.'%',$branchCode]);
            if(!is_null($resultData) && count($resultData) > 0){
                    return true;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return  false;
    }


    function openBox(Box $box){
        try{
            $sql = new StringBuilder();
            $sql->Insert("INSERT INTO `box`(`openined_date`,`openined_amount`,created_at,users_id,branches_id)");
            $sql->Insert(" VALUES(now(),?,now(),?,?)");
            $data=[$box->amount,$box->users_id,$box->branches_id];
            $resultData = $this->repository->executeAutoIncrement($sql->toString(),$data);
            return $resultData;
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;

    }
    function closeBox(Box $box){
        try{
            $sql = new StringBuilder();
            $sql->Insert("UPDATE `box` set closure_date=now(),closure_amount=? where id=? and closure_date is null ");
            $data=[$box->amount,$box->id];
            $resultData = $this->repository->executeRowsCount($sql->toString(),$data);
            return $resultData;
          
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }

}