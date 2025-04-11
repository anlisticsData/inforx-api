<?php

namespace Repositories\Branches;

use \Datetime;
use Exception;
use Commons\Uteis;
use Models\Branch;
use Models\Service;
use Dtos\BranchesDto;
use Commons\Paginator;
use Resources\Strings;
use Resources\HttpStatus;
use Commons\StringBuilder;
use Models\ServiceBranche;
use Interfaces\IConnections;
use Requests\ServiceRequest;
use Commons\DataBaseRepository;
use Requests\ServiceUpdateRequest;
use Interfaces\Branches\IBranchesRepository;



class BranchesRepository implements IBranchesRepository {
    private IConnections $repository;

    public function __construct() {
        $this->repository = new DataBaseRepository();
    }

    function records() {
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT count(*) as records FROM services where deleted_at is null;");
            $resultData = $this->repository->query($sql->toString(), null, false);
            return $resultData['records'];
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        
        return 0;
    }

   function recordsIdOurCnpjOurEmailOurDescriptionOuPhone(BranchesDto $branch) {
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT count(*) as records FROM branches  where  ");
            $sql->Insert("   deleted_at is null and (id like ? or description like  ? or  cnpj like  ? or email like  ? or phone like  ? ) ");
            $data=[$branch->search,$branch->search,$branch->search,$branch->search,$branch->search];
            $resultData = $this->repository->query($sql->toString(), $data, false);
            return $resultData;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
    }

   function idOurCnpjOurEmailOurDescriptionOuPhone(BranchesDto $branch,Paginator $paginator) {
        try {
            $limit = sprintf(" limit %s,%s",$paginator->start,$paginator->limit);
            $sql = new StringBuilder();
            $sql->Insert("SELECT * FROM branches  where  ");
            $sql->Insert(" deleted_at is null and (id like ? or description like  ? or  cnpj like  ? or email like  ? or phone like  ?)   ".$limit."");
            $data=[$branch->search,$branch->search,$branch->search,$branch->search,$branch->search];
            $resultData = $this->repository->query($sql->toString(), $data, true);
            return $resultData;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
    }
      function created(BranchesDto $brancheDto){
        try{
            $sql = new StringBuilder();
            $sql->Insert("INSERT INTO branches(`description`,`status`,cnpj,address,phone,text_ticket,email,city,state,free_time,costumers_id,available_vacancies,insurance_expiration,`created_at`,avatar_id) ");
            $sql->Insert(" VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,now(),?)");
            $data=[
                $brancheDto->description,1,$brancheDto->cnpj,$brancheDto->address,$brancheDto->phone,$brancheDto->text_ticket,
                $brancheDto->email,$brancheDto->city,$brancheDto->state,$brancheDto->free_time,$brancheDto->costumers_id,
                $brancheDto->available_vacancies,$brancheDto->insurance_expiration,$brancheDto->avatar_id
            ];
            $resultData = $this->repository->executeAutoIncrement($sql->toString(),$data);
            return $resultData;
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }

    function updateService(Service $service){

        
        $data=null;
        try{
            $sql = new StringBuilder();
            if($service->execution_time_minutes==0){
                $sql->Insert("UPDATE services set `description`=?,`price`=?");
                $sql->Insert(" where id=? and fk_customers=?");
                $data=[
                  $service->description,
                  $service->price,
                  $service->id,
                  $service->customer_id,
                ];
            }else{
                $sql->Insert("UPDATE services set `description`=?,`price`=?,execution_time_minutes=?");
                $sql->Insert(" where id=? and fk_customers=?");
                $data=[
                  $service->description,
                  $service->price,
                  $service->execution_time_minutes,
                  $service->id,
                  $service->customer_id,
                ];
            }
            $resultData = $this->repository->execute($sql->toString(),$data);
            return $resultData;
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }
    function createService(Service $service){
        try{

            
            $sql = new StringBuilder();
            $sql->Insert("INSERT INTO services(`description`,`price`,`status`,`execution_time_minutes`,`fk_customers`,`created_at`) ");
            $sql->Insert(" VALUES(?,?,?,?,?,now())");
            $data=[
               $service->description,
               $service->price,
               $service->status,
               $service->execution_time_minutes,
               $service->customer_id
            ];

           
            $resultData = $this->repository->executeAutoIncrement($sql->toString(),$data);
            return $resultData;
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }

    
    function update(BranchesDto $brancheDto){
       
        try{
            $sql = new StringBuilder();
            if($brancheDto->avatar_id=='null'){
                $sql->Insert("UPDATE branches set `description`=?,`address`=?,phone=?,text_ticket=?,city=?,`state`=?,");
                $sql->Insert("free_time=?,available_vacancies=?,insurance_expiration=? where id=?");
                $data=[
                    $brancheDto->description,$brancheDto->address,$brancheDto->phone,$brancheDto->text_ticket,
                    $brancheDto->city,$brancheDto->state,$brancheDto->free_time,$brancheDto->available_vacancies,
                    $brancheDto->insurance_expiration,$brancheDto->id
                ];
            }else{

                
                $sql->Insert("UPDATE branches set `description`=?,`address`=?,phone=?,text_ticket=?,city=?,`state`=?,");
                $sql->Insert("free_time=?,available_vacancies=?,insurance_expiration=?,avatar_id=? where id=?");
                $data=[
                    $brancheDto->description,$brancheDto->address,$brancheDto->phone,$brancheDto->text_ticket,
                    $brancheDto->city,$brancheDto->state,$brancheDto->free_time,$brancheDto->available_vacancies,
                    $brancheDto->insurance_expiration,$brancheDto->avatar_id,$brancheDto->id
                ];

             


              

            }



            $resultData = $this->repository->execute($sql->toString(),$data);
            return $resultData;
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }

    function deleteService($id,$customer_id){
        try{
            $sql = new StringBuilder();
            $sql->Insert("UPDATE services set `deleted_at`=now() where id=? and fk_customers=?");
            $data=[$id,$customer_id];
           
            $resultData = $this->repository->execute($sql->toString(),$data);
            return $resultData;
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }

    function deleteBranche($branchId){
        try{

            $sql = new StringBuilder();
            $sql->Insert("UPDATE branches set `deleted_at`=now() where id=? and deleted_at is null");
            $data=[$branchId];
            $resultData = $this->repository->execute($sql->toString(),$data);
            return $resultData;
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }

    function serviceBy($id,$customer_id){
        $resultData=[];
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT *  FROM services where `id`=? and fk_customers=? and deleted_at is null ");
            $data = $this->repository->query($sql->toString(), array(trim($id),$customer_id));
            if(count($data) > 0){
                foreach($data as $key =>$row){
                    $resultData[]=new Service($row);
                } 
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return $resultData;
    }


    
    function serviceDescription($description,$customer_id){
        $resultData=[];
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT *  FROM services where `description`=? and fk_customers=? and deleted_at is null ");
            $data = $this->repository->query($sql->toString(), array(trim($description),$customer_id));
            if(count($data) > 0){
                foreach($data as $key =>$row){
                    $resultData[]=new Service($row);
                } 
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return $resultData;
    }
    function services($branchId,Paginator $paginator){
        $resultData=[];
        try {
            $limit = sprintf(" %s,%s",$paginator->start,$paginator->limit);
            $sql = new StringBuilder();
            $sql->Insert("SELECT *  FROM services where branches_id=? and deleted_at  is null  limit ".$limit);
            $data = $this->repository->query($sql->toString(), array($branchId));
            if(count($data) > 0){
                foreach($data as $key =>$row){
                    $resultData[]=new Service($row);
                } 
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return $resultData;
    }



    function SearchByCustomer($branchId,$customerId){
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT *  FROM branches where id=? and costumers_id=? and deleted_at is null");
            $resultData = $this->repository->query($sql->toString(), array($branchId,$customerId));
            return $resultData;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
    }



    function SearchByServicesToCodeORNameOrDescription($params){
            $where = sprintf(" deleted_at is null and  (id like '%s%%' or description like '%s%%' or price like '%s%%' or created_at like '%s%%') ",$params,$params,$params,$params); 
            try {
                $sql = new StringBuilder();
                $sql->Insert("SELECT *  FROM services where ".$where."");
                $resultData = $this->repository->query($sql->toString());
                return $resultData;
            } catch (Exception $e) {
                throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
            }
    }
    function SearchByCustomerBy($customerId){
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT *  FROM services where  fk_customers=? and deleted_at is null");
            $resultData = $this->repository->query($sql->toString(), array($customerId));
            return $resultData;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
    }

    function SearchByCostumer($costumerId){
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT `id`, `description`, `cnpj`, `address`, `status` FROM branches where costumers_id=? and deleted_at is null");
            $resultData = $this->repository->query($sql->toString(), array($costumerId));

           
            return $resultData;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
    }

    function SearchBranchCnpjBy($cnpj){
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT `id`, `description`, `cnpj`, `address`, `status` FROM branches where cnpj=? and deleted_at is null;");
            $resultData = $this->repository->query($sql->toString(), array($cnpj));
            if(is_null($resultData) || count($resultData)==0) return null;
            $resultData =  new Branch($resultData);
            return $resultData;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
    }

    function SearchBy($branchId){
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT * FROM branches where id=? and deleted_at is null");
            $resultData = $this->repository->query($sql->toString(), array($branchId));
            return $resultData;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
    }

    function CheckExistsBranches($branchId) {
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT count(*) as records FROM branches where id=? and deleted_at IS  NULL;");
            $resultData = $this->repository->query($sql->toString(), array($branchId), false);
            if ($resultData["records"] !== 0) {
                return true;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }

        return false;
    }

    function hasAssociateServicesWithTheBranch(ServiceBranche $serviceBranche){
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT count(*) as records FROM branches_services where (services_id=? and  branches_id=?) and deleted_at IS  NULL;");
            $resultData = $this->repository->query($sql->toString(), array( $serviceBranche->services_id, $serviceBranche->branches_id), false);
            if ($resultData["records"] !== 0) {
                return true;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return false;
    }
    function associateServicesWithTheBranch(ServiceBranche $serviceBranche){
        try{
            if($this->hasAssociateServicesWithTheBranch($serviceBranche)){
                throw new Exception(Strings::$STR_BRANCHES_COSTUMER_SERVICE_ASSOCIATION_EXISTS, HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
            }
            $sql = new StringBuilder();
            $sql->Insert("INSERT INTO branches_services (created_at, price, status_service,services_id, branches_id) ");
            $sql->Insert(" VALUES(?,?,?,?,?)");
            $data=[
                $serviceBranche->created_at,
                $serviceBranche->price,
                $serviceBranche->status_service,
                $serviceBranche->services_id,
                $serviceBranche->branches_id,
            ];
            $resultData = $this->repository->executeAutoIncrement($sql->toString(),$data);
            return $resultData;
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }

    function deleteAssociateServicesWithTheBranch(ServiceBranche $serviceBranche){
        try{
            $sql = new StringBuilder();
            $sql->Insert(" UPDATE branches_services set `deleted_at`=now() ");
            $sql->Insert(" where (services_id=? and  branches_id=?) and deleted_at IS  NULL;");
            $data=[$serviceBranche->services_id, $serviceBranche->branches_id];
            $resultData = $this->repository->execute($sql->toString(),$data);
            return $resultData;
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }
}