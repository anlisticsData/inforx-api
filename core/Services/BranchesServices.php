<?php

namespace Services;
use \Datetime;
use Exception;
use Commons\Uteis;
use Models\Service;
use Dtos\BranchesDto;
use Commons\Paginator;
use Resources\Strings;
use Resources\HttpStatus;
use Models\ServiceBranche;
use Resources\APPLICATION;
use Dtos\BranchesServiceDto;
use Requests\ServiceRequest;
use Requests\ServiceUpdateRequest;
use Repositories\Users\UserRepository;
use Interfaces\Branches\IBranchesRepository;
use Repositories\Branches\BranchesRepository;
use Repositories\UploadRepository\UploadRepository;



class BranchesServices {
    private $UserRepository;
    private $BranchesRepository;
    private $UploadServices;
    private $CityServices;
    private $StateServices;

    function __construct() {
        $this->UserRepository = new UserRepository();
        $this->BranchesRepository = new BranchesRepository();
        $this->UploadServices =  new UploadRepository();
        $this->CityServices =  new CityServices();
        $this->StateServices =  new StateServices();
    }



    function serviceBy($id,$customer_id){
        try{ 
            $services =  $this->BranchesRepository->serviceBy($id,$customer_id);
            if(count($services) > 0){
                return $this->BranchesRepository->deleteService($id,$customer_id);
            }
            throw new Exception(Strings::$STR_BRANCHES_COSTUMER_SERVICE_CODE_NOT_FOUND,HttpStatus::$HTTP_CODE_BAD_REQUEST);
        } catch (Exception $e) {
             throw new Exception($e->getMessage(), $e->getCode());
        }
        return null;
    }

    function serviceToCodeAndCustomer($id,$customer_id){
        try{ 
            $services =  $this->BranchesRepository->serviceBy($id,$customer_id);
            if(count($services) > 0){
                return  $services;
            }
        } catch (Exception $e) {
             throw new Exception($e->getMessage(), $e->getCode());
        }
        return null;
    }
    

    function updateService(ServiceUpdateRequest $service){
        try{ 
            $serviceEntity =  new Service($service->toArray());
            $serviceEntity->customer_id = $service->customer_id;

            $services =  $this->BranchesRepository->serviceDescription($serviceEntity->description,$serviceEntity->customer_id);
            if(count($services) == 0){
                $isUpdate =  $this->BranchesRepository->updateService($serviceEntity);
                if($isUpdate > 0){
                    return $isUpdate;
                }
            }
            throw new Exception(Strings::$STR_BRANCHES_COSTUMER_SERVICE_NOT_UPDATE,HttpStatus::$HTTP_CODE_NOT_FOUND);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
        return null;
    }
    
  

    function createService(ServiceRequest $service){
        try{ 

          
            $serviceEntity =  new Service($service->toArray());
            $serviceEntity->customer_id=$service->customer_id;
            $services =  $this->BranchesRepository->serviceDescription($serviceEntity->description,$serviceEntity->customer_id);
            if(count($services) == 0){
                return $this->BranchesRepository->createService($serviceEntity);
            }
            throw new Exception(Strings::$STR_BRANCHES_COSTUMER_SERVICE_NOT_FOUND,HttpStatus::$HTTP_CODE_BAD_REQUEST);
        } catch (Exception $e) {
             throw new Exception($e->getMessage(), $e->getCode());
        }
        return null;
    }
    function services($branchId,Paginator $paginator=null){
       try{ 
           
           if(is_null($paginator)){
               $paginator =  new Paginator();
               $paginator->sizeRecords($this->BranchesRepository->records());
           }
            $recordsArray = $this->BranchesRepository->SearchBy($branchId);
            return ["results"=>$recordsArray,"pages"=>$paginator->paginator()];
        } catch (Exception $e) {
             throw new Exception($e->getMessage(), $e->getCode());
        }
        return null;
    }

    function servicesCustomers($customerId,Paginator $paginator){
        try{ 
             $paginator->sizeRecords($this->BranchesRepository->records());
             $recordsArray = $this->BranchesRepository->SearchByCostumer($customerId);
             return ["results"=>$recordsArray,"pages"=>$paginator->paginator()];
         } catch (Exception $e) {
              throw new Exception($e->getMessage(), $e->getCode());
         }
         return null;
     }


    
    
     function all(BranchesDto $branch,$pager=1){
        try {

         
            $records = $this->BranchesRepository->recordsIdOurCnpjOurEmailOurDescriptionOuPhone($branch);
            $size =$records["records"];
            $paginator =  new Paginator($pager);
            $paginator->sizeRecords($size);
            $resultData = $this->BranchesRepository->idOurCnpjOurEmailOurDescriptionOuPhone($branch,$paginator);
            $branchesArray=[];
            if(!is_null($resultData)){
                foreach($resultData as $index => $row){
                        $brancheDto= new BranchesDto();
                        $brancheDto->id = $row['id'];
                        $brancheDto->avatar_id = $row['avatar_id'];
                        $avatar=null;
                        $brancheDto->costumers_id = $row["costumers_id"];
                        $brancheDto->email = $row["email"];
                        $brancheDto->description = $row["description"];
                        $brancheDto->status = $row["status"];
                        $brancheDto->cnpj = $row["cnpj"];
                        $brancheDto->created_at = $row["created_at"];
                        $brancheDto->address = $row["address"];
                        $brancheDto->available_vacancies = $row["available_vacancies"];
                        $brancheDto->insurance_expiration = $row["insurance_expiration"];
                        $brancheDto->text_ticket = $row["text_ticket"];
                        $brancheDto->phone = $row["phone"];
                        $brancheDto->free_time = $row["free_time"];
                        $brancheDto->city = $row["city"];
                        $brancheDto->state = $row["state"];
                        if(!is_null($row["avatar_id"])){
                            $avatar =  $this->UploadServices->by($row["avatar_id"]);
                            if(isset($avatar->path)){
                                $avatar->file=APPLICATION::$APP_API.$avatar->path;
                            }
                        }
                        $brancheDto->avatar=$avatar;
                        $brancheDto->cityInfo = (isset($this->CityServices->SearchCitiesBy($brancheDto->city)[0])) ? $this->CityServices->SearchCitiesBy($brancheDto->city)[0]:null;
                        $brancheDto->stateInfo = (isset($this->StateServices->By($brancheDto->state)[0])) ? $this->StateServices->By($brancheDto->state)[0] : null;
                        
                        $branchesArray[]=$brancheDto;
                }
             
            }
            return ["results"=>$branchesArray,"pages"=>$paginator->paginator()];
        } catch (Exception $e) {
             throw new Exception($e->getMessage(), $e->getCode());
        }

        return null;
    }



    function SearchByServicesToCodeORNameOrDescription($params){
        try {
            $resultData = $this->BranchesRepository->SearchByServicesToCodeORNameOrDescription($params);
            $branchesArray=[];
            if(!is_null($resultData)){
                foreach($resultData as $index => $row){

                        $brancheDto= new BranchesServiceDto( $row);
                        $branchesArray[]=$brancheDto;
                }
             
            }
            return $branchesArray;
        } catch (Exception $e) {
             throw new Exception($e->getMessage(), $e->getCode());
        }

        return null;
    }


    function SearchByCustomerBy($customerId){
        try {
            $resultData = $this->BranchesRepository->SearchByCustomerBy($customerId);
            $branchesArray=[];
            if(!is_null($resultData)){
                foreach($resultData as $index => $row){

                        $brancheDto= new BranchesServiceDto( $row);
                        $branchesArray[]=$brancheDto;
                }
             
            }
            return $branchesArray;
        } catch (Exception $e) {
             throw new Exception($e->getMessage(), $e->getCode());
        }

        return null;
    }


    function SearchByCustomer($branchId,$customerId){
        try {
            $resultData = $this->BranchesRepository->SearchByCustomer($branchId,$customerId);
            $branchesArray=[];
            if(!is_null($resultData)){
                foreach($resultData as $index => $row){

                        $brancheDto= new BranchesDto();
                        $brancheDto->id = $row['id'];
                        $brancheDto->avatar_id = $row['avatar_id'];
                        $avatar=null;
                        $brancheDto->costumers_id = $row["costumers_id"];
                        $brancheDto->email = $row["email"];
                        $brancheDto->description = $row["description"];
                        $brancheDto->status = $row["status"];
                        $brancheDto->cnpj = $row["cnpj"];
                        $brancheDto->created_at = $row["created_at"];
                        $brancheDto->address = $row["address"];
                        $brancheDto->available_vacancies = $row["available_vacancies"];
                        $brancheDto->insurance_expiration = $row["insurance_expiration"];
                        $brancheDto->text_ticket = $row["text_ticket"];
                        $brancheDto->phone = $row["phone"];
                        $brancheDto->free_time = $row["free_time"];
                        $brancheDto->city = $row["city"];
                        $brancheDto->state = $row["state"];
                        if(!is_null($row["avatar_id"])){
                            $avatar =  $this->UploadServices->by($row["avatar_id"]);
                            if(isset($avatar->path)){
                                $avatar->file=APPLICATION::$APP_API.$avatar->path;
                            }
                        }
                        $brancheDto->avatar=$avatar;
                        $brancheDto->cityInfo = (isset($this->CityServices->SearchCitiesBy($brancheDto->city)[0])) ? $this->CityServices->SearchCitiesBy($brancheDto->city)[0]:null;
                        $brancheDto->stateInfo = (isset($this->StateServices->By($brancheDto->state)[0])) ? $this->StateServices->By($brancheDto->state)[0] : null;
                        
                        $branchesArray[]=$brancheDto;
                }
             
            }
            return $branchesArray;
        } catch (Exception $e) {
             throw new Exception($e->getMessage(), $e->getCode());
        }

        return null;
    }

    function deleteBranche($branchId){
        try{ 
            $services =  $this->BranchesRepository->SearchBy($branchId);
         
            if(count($services) > 0){
                return $this->BranchesRepository->deleteBranche($branchId);
            }
            throw new Exception(Strings::$STR_BRANCHES_COSTUMER_SERVICE_NOT_DELETED,HttpStatus::$HTTP_CODE_BAD_REQUEST);
        } catch (Exception $e) {
             throw new Exception($e->getMessage(), $e->getCode());
        }
        return null;
        
    }

    function SearchBy($branchId){
        try {
            $resultData = $this->BranchesRepository->SearchBy($branchId);

            $branchesArray=[];
            if(!is_null($resultData)){

                foreach($resultData as $index => $row){

                        $brancheDto= new BranchesDto();
                        $brancheDto->id = $row['id'];
                        $brancheDto->avatar_id = $row['avatar_id'];
                        $avatar=null;
                        $brancheDto->costumers_id = $row["costumers_id"];
                        $brancheDto->email = $row["email"];
                        $brancheDto->description = $row["description"];
                        $brancheDto->status = $row["status"];
                        $brancheDto->cnpj = $row["cnpj"];
                        $brancheDto->created_at = $row["created_at"];
                        $brancheDto->address = $row["address"];
                        $brancheDto->available_vacancies = $row["available_vacancies"];
                        $brancheDto->insurance_expiration = $row["insurance_expiration"];
                        $brancheDto->text_ticket = $row["text_ticket"];
                        $brancheDto->phone = $row["phone"];
                        $brancheDto->free_time = $row["free_time"];
                        $brancheDto->city = $row["city"];
                        $brancheDto->state = $row["state"];
                        if(!is_null($row["avatar_id"])){
                            $avatar =  $this->UploadServices->by($row["avatar_id"]);
                            if(isset($avatar->path)){
                                $avatar->file=APPLICATION::$APP_API.$avatar->path;
                            }
                        }
                        $brancheDto->avatar=$avatar;
                        $brancheDto->cityInfo = (isset($this->CityServices->SearchCitiesBy($brancheDto->city)[0])) ? $this->CityServices->SearchCitiesBy($brancheDto->city)[0]:null;
                        $brancheDto->stateInfo = (isset($this->StateServices->By($brancheDto->state)[0])) ? $this->StateServices->By($brancheDto->state)[0] : null;
                        
                        $branchesArray[]=$brancheDto;
                }
             
            }
            return $branchesArray;
        } catch (Exception $e) {
             throw new Exception($e->getMessage(), $e->getCode());
        }

        return null;
    }

    


      function update(BranchesDto $brancheDto){
         try {
            $resultData = $this->BranchesRepository->update($brancheDto);
            return $resultData;
        } catch (Exception $e) {
             throw new Exception($e->getMessage(), $e->getCode());
        }

        return null;
      }
      function created(BranchesDto $brancheDto){
         try {
            $brancheDto->insurance_expiration=date('Y-m-d', strtotime($brancheDto->insurance_expiration));
            $resultData = $this->BranchesRepository->created($brancheDto);
            return $resultData;
        } catch (Exception $e) {
             throw new Exception($e->getMessage(), $e->getCode());
        }

        return null;
      }

     function SearchBranchCnpjBy($cnpj){
        try {
            $resultData = $this->BranchesRepository->SearchBranchCnpjBy($cnpj);
            if(!is_null($resultData)){
                $brancheDto= new BranchesDto();
                $brancheDto->id = $resultData->id;
                $brancheDto->costumers_id = $resultData->costumers_id;
                $brancheDto->email = $resultData->email;
                $brancheDto->description = $resultData->description;
                $brancheDto->status = $resultData->status;
                $brancheDto->cnpj = $resultData->cnpj;
                $brancheDto->created_at = $resultData->created_at;
            }
            return $resultData;
        } catch (Exception $e) {
             throw new Exception($e->getMessage(), $e->getCode());
        }

        return null;
    }


    
    
    function SearchByCostumer($costumerId){
        try {
            $resultData = $this->BranchesRepository->SearchByCostumer($costumerId);
            return $resultData;
        } catch (Exception $e) {
             throw new Exception($e->getMessage(), $e->getCode());
        }

        return null;
    }

    function CheckExistsBranches($branchId) {
        try {
            $resultData = $this->BranchesRepository->CheckExistsBranches($branchId);
            return $resultData;
        } catch (Exception $e) {
             throw new Exception($e->getMessage(), $e->getCode());
        }

        return null;
    }


    public function associateServicesWithTheBranch($service,$customer){
        $serviceBranche =  new ServiceBranche();
        $serviceBranche->services_id = $service->id;
        $serviceBranche->branches_id = $customer->id;
        $serviceBranche->price = $service->price;
        $serviceBranche->status_service=APPLICATION::$APP_STATUS_NEW;
        try {
            $resultData = $this->BranchesRepository->associateServicesWithTheBranch($serviceBranche);
            return $resultData;
        } catch (Exception $e) {
             throw new Exception($e->getMessage(), $e->getCode());
        }
        return null;
    }

    public function deleteAssociateServicesWithTheBranch($service,$customer){
        $serviceBranche =  new ServiceBranche();
        $serviceBranche->services_id = $service->id;
        $serviceBranche->branches_id = $customer->id;
        try {
            if(!$this->BranchesRepository->hasAssociateServicesWithTheBranch($serviceBranche)){
                throw new Exception(Strings::$STR_BRANCHES_COSTUMER_SERVICE_ASSOCIATION_NOT_EXISTS, HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
            }
            $resultData = $this->BranchesRepository->deleteAssociateServicesWithTheBranch($serviceBranche);
            return $resultData;
        } catch (Exception $e) {
             throw new Exception($e->getMessage(), $e->getCode());
        }
        return null;
    }





}   