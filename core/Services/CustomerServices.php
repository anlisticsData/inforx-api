<?php

namespace Services;

use Dtos\CustomerDto;
use Exception;
use Interfaces\IServices;
use Models\User;
use Repositories\Customers\CustomerRepository;
use Resources\APPLICATION;
use Resources\HttpStatus;

class CustomerServices implements IServices{
    private $repository;
    private $SevicesUser;
    function __construct()
    {
        $this->repository =  new CustomerRepository();
        $this->SevicesUser = new UserServices();
    }

    function create(CustomerDto $customerDto){
        $customerDto->group_id=APPLICATION::$APP_CODE_DEFAULT_CUSTOMERS;
        $resultData =  $this->repository->created($customerDto);
        
        if($resultData > 0){
            $user = new User();
            $user->name =  $customerDto->description;
            $user->email = $customerDto->email;
            $user->password = $customerDto->password;
            $user->customer_id = $resultData;
            $user->groups_id = APPLICATION::$APP_CODE_DEFAULT_CUSTOMERS;
            $user->branches_id=0;
            $user->state = APPLICATION::$APP_CODE_ACTIVE_STATE;
            return $this->SevicesUser->created($user);
        }
    }

    function CheckExistsCostumer($customerId) {
        try {
            $resultData = $this->repository->CheckExistsCustomer($customerId);
            return $resultData;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }

        return null;
    }
}