<?php

namespace Repositories\Customers;

use Exception;
use Commons\Uteis;
use Models\Customer;
use Dtos\CustomerDto;
use Resources\HttpStatus;
use Commons\StringBuilder;
use Commons\ValidateInputs; 
use Interfaces\IConnections;
use Commons\DataBaseRepository;
use Interfaces\Customers\ICustomerRepository;
 
 

class CustomerRepository implements ICustomerRepository{
    private IConnections $repository;
    public function __construct()
    {
        $this->repository =  new DataBaseRepository();
    }

    function records()
    {
        try{
            $sql = new StringBuilder();
            $sql->Insert("SELECT count(*) as records FROM costumers;");
            $resultData =$this->repository->query($sql->toString(),null,false);
            return $resultData['records']; 
          
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return 0;
    }
    
    function created(CustomerDto $customer){
        try{
            $sql = new StringBuilder();
            $sql->Insert("INSERT INTO customers(`description`,cnpj,`address`,`state`,created_at,group_id)");
            $sql->Insert(" VALUES(?,?,?,?,now(),?)");
            $cnpjOrCpf = ValidateInputs::clearCnpjOrCpf($customer->cnpj);

            $data=[$customer->description,$cnpjOrCpf,$customer->address,$customer->state,$customer->group_id];
            $resultData = $this->repository->executeAutoIncrement($sql->toString(),$data);
            
            return $resultData;
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }

    function CheckExistsCustomer($customerId) {
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT count(*) as records FROM customers where id=? and deleted_at IS NULL;");
            $resultData = $this->repository->query($sql->toString(), array($customerId), false);

            if ($resultData["records"] !== 0) {
                return false;
            }

            return true;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
    }

    function by($customerId) {
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT * FROM customers where id=? and deleted_at IS NULL;");
            $resultData = $this->repository->query($sql->toString(), array($customerId), false);
            return new Customer($resultData);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }

}


