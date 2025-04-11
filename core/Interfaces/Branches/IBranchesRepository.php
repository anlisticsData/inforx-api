<?php

namespace Interfaces\Branches;
use Models\Service;
use Dtos\BranchesDto;
use Commons\Paginator;
use Models\ServiceBranche;
use Requests\ServiceUpdateRequest;
 
interface IBranchesRepository {
    function records();
    function SearchBy($branchId);
    function serviceDescription($description,$customer_id);
    function serviceBy($id,$customer_id);
    function SearchByCustomerBy($customerId);
    function SearchByCustomer($branchId,$customerId);
    function deleteService($id,$costumer_id);
    function deleteBranche($branchId);
    function SearchByCostumer($costumerId);
    function services($branchId,Paginator $paginator);
    function CheckExistsBranches($branchId);
    function SearchBranchCnpjBy($cnpj);
    function created(BranchesDto $brancheDto);
    function update(BranchesDto $brancheDto);
    function createService(Service $service);
    function updateService(Service $service);
    function SearchByServicesToCodeORNameOrDescription($params);
    function associateServicesWithTheBranch(ServiceBranche $serviceBranche);
    function deleteAssociateServicesWithTheBranch(ServiceBranche $serviceBranche);
    function hasAssociateServicesWithTheBranch(ServiceBranche $serviceBranche);


}